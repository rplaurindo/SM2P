<?php

namespace SM2P;

abstract class AbstractElement {

    abstract function accept(AbstractStreamingVisitor $visitor);

}
