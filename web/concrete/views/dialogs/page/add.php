<?
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="ccm-ui">

    <? if (count($frequentPageTypes) || count($otherPageTypes)) { ?>

    <? if (count($frequentPageTypes) && count($otherPageTypes)) { ?>
        <h5><?=t('Commonly Used')?></h5>
    <? } ?>

    <ul class="item-select-list">

        <? foreach($frequentPageTypes as $pt) { ?>
            <li><a href="<?=URL::to('/ccm/system/page/', 'create', $pt->getPageTypeID(), $c->getCollectionID())?>"><i class="fa fa-file-o"></i> <?=$pt->getPageTypeName()?></a></li>
        <? } ?>

        <? if (count($frequentPageTypes) && count($otherPageTypes)) { ?>
            </ul>
            <h5><?=t('Other')?></h5>
            <ul class="item-select-list">
        <? } ?>

        <? foreach($otherPageTypes as $pt) { ?>
            <li><a href="<?=URL::to('/ccm/system/page/', 'create', $pt->getPageTypeID(), $c->getCollectionID())?>"><i class="fa fa-file-o"></i> <?=$pt->getPageTypeName()?></a></li>
        <? } ?>
    </ul>

    <? } else { ?>
        <p><?=t('You do not have access to add any page types beneath the selected page.')?></p>

    <? } ?>
</div>