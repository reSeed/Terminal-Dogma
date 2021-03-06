main.directive('navbarLeft',['$swipe','$timeout',function($swipe,$timeout){
	return {
		restrict: 'AEC',
		link: function($scope, $element, $attrs)
		{
			$element.addClass('animated-class-fastest');
			$element.css('left', 0);
			
			var startPosition = {};
			var hasMoved = false;
			var isOpened = false;
			var left;
			var menuWidth = $element.find('#menu').width();
			var buttonStart = $element.find('#navbar-toggle')[0].offsetTop;
			var buttonEnd = buttonStart + $element.find('#navbar-toggle')[0].offsetHeight;
			var interfaceWidth = parseInt($element.width());
			var haveToSwipeLeft = false;
			var isAnimating = false;

			var swipeLeft = function()
			{
				isAnimating = true;
				$timeout(function(){
					$element.css('left',0);
					$element.width(interfaceWidth);
					$timeout(function(){
						$('#footer .tab').css('z-index',103);
						isAnimating = false;
					},250);						
				});	
			};
			
			var swipeRight = function()
			{
				isAnimating = true;
				$timeout(function(){
					$element.css('left',  menuWidth);
					$element.width('100%');		
					$('#footer .tab').css('z-index',99);
					$timeout(function(){
						isAnimating = false;
					},250);
				});
			};
			
			$scope.$on('close-navbar',function(){ haveToSwipeLeft = true; });
			
			$swipe.bind($element, {
				start : function(coordinates)
				{
					if(isAnimating) return;
					left = $element.css('left');
					
					isOpened = (left !== '0px' && left !== 'auto');
					
					startPosition.x = isOpened ? coordinates.x - parseInt(left) : coordinates.x;
					startPosition.y =  coordinates.y;
					
//					console.log(coordinates.x, left, startPosition.x, isOpened);
				},
				move : function(coordinates)
				{
					if(isAnimating) return;
//					console.log(startPosition.x);

					if(haveToSwipeLeft && !isOpened) haveToSwipeLeft = false;
					if( !haveToSwipeLeft && ( startPosition.x < 8 || ( startPosition.y < buttonEnd && startPosition.y > buttonStart ) ) )
					{
						$('#footer .tab').css('z-index',99);
						if(coordinates.x !== startPosition.x || coordinates.y !== startPosition.y) hasMoved = true;
						$element.removeClass('animated-class-fastest');
						$element.css('left', Math.max(0, Math.min(coordinates.x - startPosition.x, menuWidth)) );						
					}
				},
				end : function(coordinates,event)
				{
					if(isAnimating) return;
					left = $element.css('left');
					
					var changedOpening = (left !== '0px' && left !== 'auto') !== isOpened;
					var endPosition = isOpened ? coordinates.x - menuWidth : coordinates.x;
					isOpened = (left !== '0px' && left !== 'auto');

					$element.addClass('animated-class-fastest');	
					
					if(haveToSwipeLeft)
					{
						swipeLeft();
						if(!isOpened)
						{
							haveToSwipeLeft = false;					
						}
					}
					if(!haveToSwipeLeft)
					{						
						if(!changedOpening && startPosition.x > -1 && endPosition  > -1 && ( !isOpened ? ( startPosition.x < interfaceWidth && startPosition.y < buttonEnd && startPosition.y > buttonStart && endPosition < interfaceWidth) : true ) )
						{
							if( parseInt(left) < menuWidth/2 ) swipeRight();
							else
							{
								swipeLeft();	
							}
						}
						else
						{
//							console.log(event);
							if( parseInt(left) < menuWidth/2) swipeLeft();
							else swipeRight();
//							console.log(event.isDefaultPrevented);
						}
					}
					
					hasMoved = false;
				},
				cancel : function()
				{					
					if(isAnimating) return;
					left = $element.css('left');
					
					$element.addClass('animated-class-fastest');
					if( parseInt(left) < menuWidth/2 )
					{
						swipeLeft();			
					}
					else swipeRight();

					hasMoved = false;					
				}
			});
		}
	};
	
}]);