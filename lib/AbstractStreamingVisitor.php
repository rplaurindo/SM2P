<?php

namespace SM2P;

interface AbstractStreamingVisitor extends AbstractVisitor {

    function visit(Streaming $element);

}
