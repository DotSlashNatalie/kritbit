# Kritbit

Kritbit is a simplistic alternative to continuous integration tools like Jenkins. I've personally found Jenkins to be too cumbersome for a one man show. It's a great tool if you have the time to sit down and configure it and have it used by many people - but it was too complex for my needs. Also I felt some features were unnecessarily complex which as a result didn't work.

Kritbit was created to fill a gap of needing the ability to track and run jobs.

Kritbit has 2 purposes:

1. To run a job locally and collect stats about the run
2. To allow an external service to phone home with stats about a job that ran

1 - is suited towards running compile and test jobs

2 - is to have integration into current task scheduling systems

Note: Kritbit originally had 3 purposes - one of them being running commands remotely. But since you can run any arbitrary script you can run a command remotely via SSH or some other external service. Yes, Windows does not have SSH natively (yet?) - but you can download an implementation [here](http://www.freesshd.com/). 

Kritbit is designed to be simple and flexible. It makes no assumptions about your security and only provides minimal security procedures. I am not a crypto expert - but I make tools that work. So while I cannot guarantee that big brother won't be able to decrypt messages from external services - it should be good enough for most implementations. So please, if you find that the crypto security is less than perfect I accept patches of any size, creed, or color. The encryption technology used isn't meant to prevent a guy with a Beowulf cluster from cracking your message - but rather preventing some script kiddie with Firesheep from seeing what you are doing.

# Attributions

Kritbit uses the following projects

- [Haplous Framework](https://srchub.org/p/haplousframework/) - MIT
- [h2o template engine](https://github.com/speedmax/h2o-php) - MIT
- [DByte](https://github.com/Xeoncross/DByte) - MIT
- [AES PHP support](http://stackoverflow.com/a/8232171/195722)
- [CRON expression](https://github.com/mtdowling/cron-expression) - MIT
- [phpoauthlib2](https://srchub.org/p/phpoauthlib2/) - MIT
- [stacktraceprint](http://stackoverflow.com/a/4282133/195722)
- [Twitter Bootstrap](http://getbootstrap.com/2.3.2/)
- [jQuery](https://jquery.com/)
- [jQuery confirm](http://craftpip.github.io/jquery-confirm/)
- [bootstrap fullscreen](http://craftpip.github.io/bootstrap-fullscreen-select/)
- [dynatable](http://www.dynatable.com/)

Made with <3 by Nathan Adams