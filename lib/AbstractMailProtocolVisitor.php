<?php

namespace SM2P;

interface AbstractMailProtocolVisitor {

    function visit(MailProtocol $element);

}
