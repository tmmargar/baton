{extends file="base.tpl"}
{block name=title}Twirling for Grace {$title}{/block}
{block name=style}<link href="css/calendar.css?v={$smarty.now|date_format:'%m/%d/%Y %H:%M:%S'}" rel="stylesheet">{$style}{/block}
{block name=script}{$script}{/block}
{block name=content}
 {$content}
 {$contentAdditional}
{/block}