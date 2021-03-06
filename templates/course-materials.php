<div centered class="text-center" ng-if="course.hasAccessToMaterial && course.materials.length == 0">
	<p>Questo corso non ha ancora materiale disponibile.</p>
</div>
<div centered class="text-center" ng-if="!course.hasAccessToMaterial">
	<p>Iscriviti al corso per vedere il nostro materiale!</p>
</div>
<table class="table table-striped" ng-if="course.hasAccessToMaterial && course.materials.length > 0">
    <tr ng-repeat="material in course.materials">
        <td>
            <div class="container" id="material{{material.materialID}}" size-on-scope>
                <div class="material-title" id="material{{material.materialID}}Title" size-on-scope ng-style="course.getMargin(material{{material.materialID}}Height,material{{material.materialID}}TitleHeight)">
                    <div class="container" id="materialContainer">
                        <a ng-href="{{material.fileURI}}">
                            <span class="middler"></span>
                            <span class="middle fa fa-3x" ng-class="material.getFA()"></span>
                            <p><span class="middle" ng-bind="material.getTitle()"></span></p>
                        </a>
                    </div>
                </div>
                <div class="material-content" id="material{{material.materialID}}Content" size-on-scope ng-style="course.getMargin(material{{material.materialID}}Height,material{{material.materialID}}ContentHeight)">
                    <div class="container" ng-if="material.note">
                        <p><span ng-bind="material.note"></span></p>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>