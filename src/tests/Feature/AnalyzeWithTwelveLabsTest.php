<?php

namespace Tests\Feature;

use App\Actions\AnalyzeWithTwelveLabs;
use App\Enums\Status;
use App\Models\Process;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnalyzeWithTwelveLabsTest extends TestCase
{
    use RefreshDatabase;

    private function process(array $options = []): Process
    {
        Storage::fake('local');
        Storage::disk('local')->put('video/sample.mp4', 'fake-bytes');

        return Process::create([
            'options' => array_merge([
                'provider' => 'twelvelabs',
                'subtitles' => true,
                'summary' => true,
                'chapters' => true,
                'chapters_amount' => 2,
                'language' => 'default',
            ], $options),
            'file' => 'video/sample.mp4',
        ]);
    }

    public function test_it_passes_through_when_no_api_key_is_configured(): void
    {
        config(['services.twelvelabs.api_key' => null]);
        Http::fake();

        $process = $this->process();
        $reached = false;

        (new AnalyzeWithTwelveLabs)->handle($process, function ($p) use (&$reached) {
            $reached = true;

            return $p;
        });

        $this->assertTrue($reached, 'OpenAI pipeline should continue when TwelveLabs is not configured.');
        Http::assertNothingSent();
    }

    public function test_it_passes_through_when_provider_is_openai(): void
    {
        config(['services.twelvelabs.api_key' => 'tl-test']);
        Http::fake();

        $process = $this->process(['provider' => 'openai']);
        $reached = false;

        (new AnalyzeWithTwelveLabs)->handle($process, function ($p) use (&$reached) {
            $reached = true;

            return $p;
        });

        $this->assertTrue($reached);
        Http::assertNothingSent();
    }

    public function test_it_produces_vtt_summary_and_chapters_in_one_pass(): void
    {
        config([
            'services.twelvelabs.api_key' => 'tl-test',
            'services.twelvelabs.base_url' => 'https://api.twelvelabs.io/v1.3',
            'services.twelvelabs.model' => 'pegasus1.5',
        ]);

        Http::fake([
            '*/assets' => Http::response(['_id' => 'asset-123', 'status' => 'processing'], 201),
            '*/assets/asset-123' => Http::response(['_id' => 'asset-123', 'status' => 'ready'], 200),
            '*/analyze' => Http::response([
                'data' => json_encode([
                    'subtitles' => [
                        ['start' => 0, 'end' => 2.5, 'text' => 'Hello world'],
                        ['start' => 2.5, 'end' => 5, 'text' => 'Second line'],
                    ],
                    'summary' => 'A short clip that greets the world.',
                    'chapters' => [
                        ['start' => 0, 'title' => 'Intro'],
                        ['start' => 2.5, 'title' => 'Greeting'],
                    ],
                ]),
            ], 200),
        ]);

        $process = $this->process();
        $next = fn ($p) => $this->fail('TwelveLabs should short-circuit the OpenAI pipeline.');

        (new AnalyzeWithTwelveLabs)->handle($process, $next);

        $process->refresh();

        $this->assertEquals(Status::COMPLETE, $process->status);
        $this->assertStringStartsWith('WEBVTT', $process->transcript);
        $this->assertStringContainsString('00:00:00.000 --> 00:00:02.500', $process->transcript);
        $this->assertStringContainsString('Hello world', $process->transcript);
        $this->assertEquals('A short clip that greets the world.', $process->summary);
        $this->assertStringContainsString('00:00:00.000 Intro', $process->chapters);
        $this->assertStringContainsString('00:00:02.500 Greeting', $process->chapters);
    }

    public function test_it_omits_summary_and_chapters_when_not_requested(): void
    {
        config(['services.twelvelabs.api_key' => 'tl-test']);

        Http::fake([
            '*/assets' => Http::response(['_id' => 'a1', 'status' => 'ready'], 201),
            '*/assets/a1' => Http::response(['_id' => 'a1', 'status' => 'ready'], 200),
            '*/analyze' => Http::response([
                'data' => json_encode([
                    'subtitles' => [['start' => 0, 'end' => 1, 'text' => 'Hi']],
                    'summary' => 'unwanted',
                    'chapters' => [['start' => 0, 'title' => 'unwanted']],
                ]),
            ], 200),
        ]);

        $process = $this->process(['summary' => false, 'chapters' => false]);

        (new AnalyzeWithTwelveLabs)->handle($process, fn ($p) => $p);

        $process->refresh();

        $this->assertNull($process->summary);
        $this->assertNull($process->chapters);
        $this->assertStringContainsString('Hi', $process->transcript);
    }

    public function test_it_marks_the_process_errored_on_api_failure(): void
    {
        config(['services.twelvelabs.api_key' => 'tl-test']);

        Http::fake([
            '*/assets' => Http::response(['message' => 'bad request'], 400),
        ]);

        $process = $this->process();

        (new AnalyzeWithTwelveLabs)->handle($process, fn ($p) => $p);

        $process->refresh();

        $this->assertEquals(Status::ERRORED, $process->status);
        $this->assertNotEmpty($process->error);
    }
}
