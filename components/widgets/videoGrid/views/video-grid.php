<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $pageCount int */
/* @var $page int */
/* @var $pageSize int */
/* @var $mode string */
/* @var $direction string */

?>
<div class="js--video-grid"
     data-page-count="<?=$pageCount;?>"
     data-page="<?=$page;?>"
     data-mode="<?=$mode;?>"
     data-direction="<?=$direction;?>"
     data-page-size="<?=$pageSize;?>"
>
    <script class="js--navigation-tpl" type="text/x-handlebars-template">
        <nav>
            <ul class="pagination">
                {{#if paginator.hasPrev}}
                    <li class="page-item">
                        <a class="page-link" href="javascript:" data-action="page" data-page="{{paginator.getPrev}}">prev</a>
                    </li>
                {{/if}}
                {{#each paginator.firstPages}}
                    <li class="page-item{{#if active}} active{{/if}}">
                        <a class="page-link" href="javascript:" data-action="page" data-page="{{index}}">{{title}}</a>
                    </li>
                {{/each}}
                {{#if paginator.hasPrevSpacer}}
                    <li class="page-item disabled">
                        <span class="page-link" href="javascript:">...</span>
                    </li>
                {{/if}}
                {{#each paginator.middlePages}}
                    <li class="page-item{{#if active}} active{{/if}}">
                        <a class="page-link" href="javascript:" data-action="page" data-page="{{index}}">{{title}}</a>
                    </li>
                {{/each}}
                {{#if paginator.hasNextSpacer}}
                    <li class="page-item disabled">
                        <span class="page-link" href="javascript:">...</span>
                    </li>
                {{/if}}
                {{#each paginator.lastPages}}
                    <li class="page-item{{#if active}} active{{/if}}">
                        <a class="page-link" href="javascript:" data-action="page" data-page="{{index}}">{{title}}</a>
                    </li>
                {{/each}}
                {{#if paginator.hasNext}}
                    <li class="page-item">
                        <a class="page-link" href="javascript:" data-action="page" data-page="{{paginator.getNext}}">next</a>
                    </li>
                {{/if}}
            </ul>
            <ul class="pagination">
                {{#each modeManager.modes}}
                    <li class="page-item{{#if active}} active{{/if}}">
                        <a class="page-link" href="javascript:" data-action="mode" data-mode="{{mode}}" data-direction="{{direction}}">{{{title}}}</a>
                    </li>
                {{/each}}
            </ul>
            <ul class="pagination">
                {{#each modeManager.pageSizes}}
                    <li class="page-item{{#if active}} active{{/if}}">
                        <a class="page-link" href="?page_size={{pageSize}}">{{{title}}}</a>
                    </li>
                {{/each}}
            </ul>
        </nav>
    </script>
    <script class="js--grid-tpl" type="text/x-handlebars-template">
        <div class="row">
            {{#if videos}}
                {{#each videos}}
                    <div data-index="{{@key}}" class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="padding-top: 10px">
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="/video/{{slug}}">
                                    <img src="/images/video.svg" />
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <a href="/video/{{slug}}">
                                            <strong>
                                                {{title}}
                                            </strong>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="bg-info">
                                            {{durationFormat duration}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="text-primary pull-right">
                                            {{dateTimeFormatCustom added_at "MMM DD 'YY"}}
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="text-muted pull-right">
                                            <span class="glyphicon glyphicon glyphicon-eye-open"></span> {{views}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{/each}}
            {{else}}
                <p>
                    No videos yet...
                </p>
                <p>
                    Tip: use fixture
                </p>
                <code>yii fixture/load Video</code>
            {{/if}}
        </div>
    </script>
    <div class="js--navigation-node">
    </div>
    <div class="js--grid-node">
    </div>
    <div class="js--navigation-node">
    </div>
</div>
