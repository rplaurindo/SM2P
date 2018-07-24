<?php

namespace SM2P;

//interface AbstractMailProtocolVisitor extends AbstractStreamingVisitor {
interface AbstractMailProtocolVisitor extends AbstractVisitor {

    function visit(MailProtocol $element);

}
