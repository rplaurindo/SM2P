<?php

namespace SM2P;

interface AbstractSMTPVisitor {

    function visit(SMTP $element);

}
