msmq
====

aztech/msmq is an ongoing development attempt to create a pure PHP MSMQ client.

Why ? Because Linux and Windows should be BFF's (insert shiny unicorn/angered leprechaun/dire AIDS infected animal here)

No seriously, fo' the fun. Implementing the MSMQ protocol (well, its client part for a start, and that's a biggie actually) implies having 
the following available :

* An NTLM auth library (included here, a shameless port of Perl's NTLM module).
* A DCE-RPC client library (included here, a shameless port of Perl's RPC module).
* A DCOM layer that runs atop the DCE-RPC one (making progress here)

Hang on, I havent written the rest of the code yet... 

But currently, the only practical way (that I'm aware of) of interacting with an MSMQ server is through .NET interop using the .NET/COM extension. And that's ~~ghetto, err,~~ not portable.

## Goal

To have a portable, OS-independant (just in case portable and OS independance dont mean the same to you) MSMQ client library.

To have fun writing it. If you don't think it's fun, go see a doctor.

## Credits

The shameless ports :

- http://cpansearch.perl.org/src/UMVUE/DCE-RPC-0.11/lib/DCE/RPC.pm
- http://cpansearch.perl.org/src/UMVUE/Authen-Perl-NTLM-0.12/lib/Authen/Perl/NTLM.pm

Those two pages of code actually gave me a big headstart on that project, since they are an easily interpretable version of NTLM of RPC (unless you can't read Perl...).

Then I found those ressources :

- http://davenport.sourceforge.net/ntlm.html
- http://ubiqx.org/cifs/

Which are apparently remainders of a time when men and women were brave enough to R-E MS's protocols in the lurking night.

Anyways, a big *thank you* to those digital cavemen who have been involved with NTLM/RPC/CIFS and the likes.
