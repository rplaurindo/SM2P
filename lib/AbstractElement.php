<?php

namespace SM2P;

// Abstract Element
abstract class AbstractElement {

    abstract function accept(AbstractStreamingVisitor $visitor);

}
