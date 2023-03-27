# Subvert

Generate subtitles, chapters, and summaries of videos in seconds with the help of OpenAI.

🚧 This is very much a work-in-progress, please [create issues](https://github.com/aschmelyun/subvert/issues/new) for bugs if they appear 🚧

## Getting started

Subvert is self-contained in a single [Docker image]() and can be started with:

```
docker run -it -p 80:80 -e OPENAI_API_KEY=sk-123abc subvert
```

This will boot up a server running the application and make it available to your machine at [localhost](http://localhost).

## How's it work

After selecting a video file to process, you have the option of selecting whether you want to include chapters and a summary as well.

Your video is sent through to an API where the audio is extracted from it, and then sent to **OpenAI's Whisper model** for transcription into the common vtt format.

If you chose to select chapters or a summary, that transcript is sent to a **ChatGPT model** for processing into concise chapters of the length you wanted, and a brief summary that would fit in something like a YouTube description.

## Starting from source

Alternative, if you have **PHP 8.1+** and **npm** installed on your local machine, you can boot the application up directly from the source code instead.

First, check out this repo to your desired location. Then, navigate to the `src` directory and run:

```
./startup.sh
```

Alternatively, you can run the commands inside of the `startup.sh` script individually for the same result.

## Deploying

Because this project is contained in a single Dockerfile, it can be deployed immediately to any server provisioned with Docker. Alternatively, the Subvert Docker image can be ran on cloud instances via AWS, Azure, GCP, Fly.io, etc.

> Note: This is currently only served through the insecure :80 http port.

## License

The MIT License (MIT). Please see [License File]()