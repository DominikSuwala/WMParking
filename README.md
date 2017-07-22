# White Marigold » Take-Home Project for Software Engineer Candidates

## WMC.doc and WMC ReadMe.doc have more detailed information. Wampserver should work out of the box.

## Download Link to Wampserver

Requires VC redistributables
https://phoenixnap.dl.sourceforge.net/project/wampserver/WampServer%203/WampServer%203.0.0/wampserver3.0.6_x64_apache2.4.23_mysql5.7.14_php5.6.25-7.0.10.exe

Welcome!

## Problem

We need a Web application that displays “real-time” (low latency) parking garage data. The parking
events — cars entering and exiting spaces — come from a [Kafka](https://kafka.apache.org/) topic.
We suggest you build a local database of parking event history.

## Deliverables

Please deliver:

* A program that will:
    * Read events from the Kafka topic
    * Store the events in a local database
    * Derive “visit” records (see below) from the events and store them in the database

* A Web page or Web site that displays:
    * Current overall occupancy
    * Current occupancy of each bay
    * A scrolling list of events as they occur
    * BONUS: A search form for visits

## Guidelines

* You do not need to “finish” the project.
  * We care less about the end result being complete than about how you approach the work that you
    do. Approach this project as if it was a real project that will eventually go to production and
    your co-workers will need to maintain it. It’s fine if you don’t finish it. Just make sure that
    the path towards finishing it is clear; supply perhaps a to-do list or an informal project plan.
* You are not expected to demonstrate expertise in all aspects of the project
  * We know that everyone has strengths and weaknesses; that’s why we collaborate as a team. Don’t
    feel that every aspect of the project needs to be amazing; it doesn’t. This isn’t a competition
    and no one can win or lose, pass or fail. The goal is for us to evaluate where you are _right
    now_ and to experience what it’s like to work with you on a project. So just try to meet the
    requirements and move on.
* Please document both components just as you would with a normal project that your teammates would
  have to maintain sooner or later.
* Please add the components and documentation to this git repository, committing regularly with
  descriptive messages, and pushing to GitHub regularly, just as you would with a normal project.
* Feel free to use any programming language(s), libraries, databases, etc. of your choosing.
  * We suggest you use whatever you’re most familiar with.
* Treat this like any normal project: it’s “open book” and don’t hesitate to discuss it with the
  team us.

## Data

The Kafka broker provides a strictly ordered data stream, called “topics” in Kafka jargon: `bay_events`

All records in the topic are stringified JSON objects.

### Bay Events

A “bay event” means someone parking or leaving a space. You could think of it as a parking event.

*Entry events* have `type` set to `entry` and *exit events* have `type` set to `exit`.

```json
{
  "bay_id": "integer",
  "timestamp": "datetime",
  "type": "string"
}
```

### Visits

Entry and exit bay events should be appropriately matched to become “visits”.

```json
{
  "bay_id": "integer",
  "entry_timestamp": "datetime",
  "exit_timestamp": "datetime",
  "dwell_mins": "integer"
}
```

`dwell_mins` is the difference between the entry timestamp and exit timestamp, in minutes.

## Setup

### Kafka Broker

1. Download, install, and start ZooKeeper and Kafka by following steps 1–3 in the
   [Confluent Platform Quickstart](http://docs.confluent.io/3.0.1/quickstart.html)
2. The Kafka broker is now up and running and accessible on `localhost` at port `9092`

### Bay Simulator

The Bay Simulator will generate and produce events to the topics `bay-events`. You can
start and stop it at your convenience to generate and populate data that your program will consume.

The instructions below were tested on MacOS but should also work on Unix, Linux, or Windows.

#### Requirements

1. [Ruby](https://www.ruby-lang.org/)
2. [Bundler](http://bundler.io/)

#### Setup

Just run `cd bay_simulator` then `bundle install` and you should be good to go.

#### Starting and Stopping

You can start the simulator with `ruby bay_simulator/bay_simulator.rb` and stop it with ctrl-c.

The simulator supports various options; pass `--help` for usage information.

A nice beefy scenario to test out is `--bay-count 5000 --occupied-time 600`.
