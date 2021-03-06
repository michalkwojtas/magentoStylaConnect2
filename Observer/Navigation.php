<?php
namespace Styla\Connect2\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Event\ObserverInterface;

class Navigation implements ObserverInterface
{
    protected $configHelper;
    protected $request;

    public function __construct(
        \Styla\Connect2\Helper\Config $configHelper,
        \Magento\Framework\App\Request\Http $request
    )
    {
        $this->configHelper = $configHelper;
        $this->request      = $request;
    }

    /**
     * This will add a navigation link for the styla magazine in the main navigation tree.
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        if (!$this->configHelper->isNavigationLinkEnabled()) {
            return $this;
        }

        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getMenu();

        $tree        = $menu->getTree();
        $magazineUrl = $this->configHelper->getFullMagazineUrl();
        $data        = [
            'name'      => $this->configHelper->getNavigationLinkLabel(),
            'id'        => 'styla-magazine',
            'url'       => $magazineUrl,
            'is_active' => $this->request->getControllerModule() == "Styla_Connect2"
        ];

        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);

        return $this;
    }
}