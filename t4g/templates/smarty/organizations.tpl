{extends file="base.tpl"}
{block name=title}{$title}{/block}
{block name=style}<link href="css/organizations.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{$style}{/block}
{block name=script}<script src="scripts/organizations.js?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" type="module"></script>{/block}
{block name=content}{$content}{/block}