<?php

namespace SM2P;

interface AbstractSMTPVisitor extends AbstractVisitor {

    function visit(SMTP $element);

}
