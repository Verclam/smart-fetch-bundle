<?php

namespace Verclam\SmartFetchBundle\Fetcher\TreeBuilder\Component;

use Verclam\SmartFetchBundle\Fetcher\Visitor\SmartFetchVisitorInterface;

class Composite extends Component
{
    /**
     * @var Component[]
     */
    private array $children = [];

    public function __construct($root = false)
    {
        $this->isRoot = $root;
        parent::__construct();
    }

    public function isComposite(): bool
    {
        return true;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(ComponentInterface $child): static
    {
        $this->children[] = $child;
        $child->setParent($this);
        return $this;
    }

    public function handle(SmartFetchVisitorInterface $visitor): void
    {
        if (!$this->isInitialized()) {
            $visitor->fetchResult($this);
        }

        foreach ($this->children as $child) {
            if($child->isScalar()){
                continue;
            }
            $child->handle($visitor);
        }
        
        //If this is a root node, it mean's here that
        //we reached the end of the tree, so we can now process the results
        //for example in arrayMode, we need to join the results
        // of all the nodes to the root node.
        if($this->isRoot()){
            $visitor->processResults($this);
        }
    }
}