{extends file="base.tpl"}
{block name=title}{$title}{/block}
{block name=style}<link href="css/home.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{$style}{/block}
{block name=content}{$content}{/block}