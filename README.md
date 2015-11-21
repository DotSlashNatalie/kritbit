# Kritbit

Kritbit is a simplistic alternative to continuous integration tools like Jenkins. I've personally found Jenkins to be too cumbersome for a one man show. It's a great tool if you have the time to sit down and configure it and have it used by many people - but it was too complex for my needs.

Kritbit was created to fill a gap of needing the ability to track and run jobs.

Kritbit has 3 purposes:

1. To run a job locally and collect stats about the run
2. To run a job remotely and collect stats about the run
3. To allow an external service to phone home with stats about a job that ran

#1 is suited towards running compile and test jobs
#2 is to be crossplatform and thus will have a service that can be installed on systems. This will also have the ability to "hot run" a command remotely.
#3 is to have integration into current task scheduling systems

Kritbit is designed to be simple and flexible. It makes no assumptions about your security and only provides minimal security procedures. I am not a crypto expert - but I make tools that works. So while I cannot guarantee that big brother won't be able to decrypt messages from external services - it should be good enough for most implementations. So please, if you find that the crypto security is less than perfect I accept patches of any size, creed, or color.