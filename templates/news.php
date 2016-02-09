<div id="news-events" ng-controller="newsController as news" spinner="news.getAjax">
	<spinner-place class="fa-5x"></spinner-place>
	<spinner-final>
		<div ng-repeat="row in news.newsByRow" class="black">
			<div class="news" ng-repeat="event in row" ng-init="index=$index"  ng-class="presentation.spacedTabs($first)">
				<div  class="bg-leaf">
					<div class="container">
						<div class="news-icon" centered><i class="fa white" ng-class="news.getIconClass(event)" fittext fittext-exclusive="width"></i></div>
						<div class="news-title text-left" centered>
							<h6><b  class="white" ng-bind-html="event.title"></b></h6>
						</div>
					</div>
					<p ng-if="event.type==='event'" class="darker-grey">
						<small>
							<b class="white">Quando: </b><span class="black" ng-bind="news.getDate(event)"></span>
						</small><br />
						<small>
							<b class="white">Dove: </b><span class="black" ng-bind="event.place"></span>
						</small>
					</p>
				</div>
				<div class="news-description bg-dark-leaf">
					<span ng-bind="event.description"></span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</spinner-final>
</div>