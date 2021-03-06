main.directive('gridsterAutoResize',['$timeout',function($timeout){
	return {
		restrict: 'A',
		link: function($scope,$element,$attr)
		{
			var itemID = $scope.gridsterItems[$scope.index].id;
			var event;
			switch(itemID)
			{
				case 'courseDescription':
					event = 'course';
					break;
				case 'courseTeacher':
					event = 'teacher';
					break;
				case 'calendar':
					event = 'calendar';
					break;
				case 'courseNotifications':
					event = 'notifications';
					break;
				case 'courseMaterials':
					event = 'materials';
					break;
				case 'courseBanner':
					event = 'course';
					break;
				case 'profileSummary':
					event = 'summary';
					break;
				case 'profileNotifications':
					event = 'notifications';
					break;
				case 'profileAchievements':
					event = 'achievements';
					break;
				case 'profileRewards':
					event = 'rewards';
					break;
			}
			
			/* Sets perfectScrollbar to panel */
			var scrollbarCreated = false;
			var updateScrollbar = function()
			{
				var container = $element.children('.container');
				var panel = $element.find('.panel-title');				
				$element.find('.scrollbar-wrapper').height(container.height() - panel.height() - 20);
				
				var scrollbar = $element.find('.scrollbar');
				if(!scrollbarCreated)
				{
					scrollbar.perfectScrollbar({
						suppressScrollX: true
					});
					scrollbarCreated = true;
				}
				else
				{
					scrollbar.perfectScrollbar('update');
				}
			};
			
			
			$scope.$on(event,function()
			{
				if($scope.measuresLoaded) return;
//				console.log('GridsterResize: event ', event);
				
				var content = $element.find('.panel-content');
				var panelHeader = $element.find('.panel-title');
				
				var innerHeight = content.innerHeight();
				var panelHeaderHeight = panelHeader.height();
				
				/* Resize Gridster item to fit its content */
				var resize = function()
				{
//					console.log(itemID + ' - Resize : sono stato chiamato');
					var gridsterItem = $scope.gridsterItem;
					if(!gridsterItem) return;
					var exactRows = Math.min(gridsterItem.gridster.pixelsToRows(innerHeight+panelHeaderHeight+40,true),6);
	//				console.log(exactRows);
					$timeout(function(){gridsterItem.setSizeY(exactRows);});
							
					$timeout(function(){
						var item = {};
						item.measures = JSON.stringify(gridsterItem.toJSON());
//						console.log($scope, $scope.gridsterItems, $scope.index);
						item.id = $scope.gridsterItems[$scope.index].id;
						$scope.registerMeasures(item);
						$timeout(function(){
							$scope.measuresLoaded = true;
						});
					});
				};
				
				$scope.$watch(
					function()
					{
						if(content) return content.height(); 
					},
					function(newValue, oldValue)
					{
						innerHeight = newValue;
//						console.log(itemID + ' - watch content : chiamo Resize con ',newValue, ' e ',oldValue);
						resize();
					}
				);
				
			});
			
			$scope.$on('measuresLoaded', function(){
				$scope.measuresLoaded = true;
				
				$scope.$watchCollection(
					function()
					{
						return [$element[0].offsetHeight, $element[0].offsetWidth];
					},
					function()
					{
						$timeout(updateScrollbar());
					}
				);
			});
			
			/* Watches for user changes */
			$scope.$on('gridster-item-transition-end',function(){
				if($scope.measuresLoaded)
				{
					updateScrollbar();
					$scope.registerAllMeasures($scope.gridster.grid);
				}
			});
			
		}
	}
}]);