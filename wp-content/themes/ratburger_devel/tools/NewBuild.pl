#! /usr/bin/perl

    use strict;
    use warnings;

    #  Update build information after a Git commit

    my $arby = "$ENV{HOME}/rb";
    my $buildno = `cd $arby; git rev-list --all --count`;
    chomp($buildno);
    my $buildate = `cd $arby;  git log -1 HEAD --date=iso | grep Date:`;
    chomp($buildate);
    $buildate =~ m/^Date:\s+(.+)\s+\+/;
    $buildate = "$1";
    $buildate =~ s/:\d\d$//;
    my $buildcommit = `cd $arby;  git log -1 HEAD | egrep "^commit\\s"`;
    $buildcommit =~ m/^commit\s+(\w+)\s+/;
    $buildcommit = $1;

#   print(STDERR "Build $buildno\n");
#   print(STDERR "Date $buildate\n");
#   print(STDERR "Commit $buildcommit\n");

    open(FO, ">$arby/wp-content/themes/ratburger_devel/ratburger/build.php") ||
        die("Cannot create $arby/wp-content/themes/ratburger_devel/ratburger/build.php");
    print(FO "<?php\n");
    print(FO "\$rb_build_number = '$buildno';\n");
    print(FO "\$rb_build_time = '$buildate';\n");
    print(FO "\$rb_build_commit = '$buildcommit';\n");
    print(FO "?>\n");
    close(FO);

    print(STDERR "Build $buildno ($buildate UTC)\n");
