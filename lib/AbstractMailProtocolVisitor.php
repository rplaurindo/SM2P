<?php

namespace SM2P;

interface AbstractMailProtocolVisitor extends AbstractVisitor {

    function visit(MailProtocol $element);

}
