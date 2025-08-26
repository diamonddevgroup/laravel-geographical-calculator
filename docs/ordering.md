Get the closest point
---------------

```php
     $closest  = \DiamondDev\GeographicalCalculator\Facade\GeoFacade::setMainPoint([22, 37])
            ->setPoints([
                [22, 37],
                [33, 40],
                // .... other points
            ])
            // and of course, you still can use getPoint again if you want.
            ->setPoint([33, 40])
            // you can use callback, the callback is return A collection instance of the result.
            ->getClosest();

     //  the result will be an array of [pointIndex => [lat, long]] that you inserted before.
     return $closest;
```

Get the farthest point
---------------

```php
     $farthest  = \DiamondDev\GeographicalCalculator\Facade\GeoFacade::setMainPoint([22, 37])
            ->setPoints([
                [22, 37],
                [33, 40],
                // .... other points
            ])
            // and of course, you still can use getPoint again if you want.
            ->setPoint([33, 40])
            // you can use callback, the callback is return A collection instance of the result.
            ->getFarthest();

     //  the result will be an array of [pointIndex => [lat, long]] that you inserted before.
     return $farthest;
```

Get ordering points by the nearest neighbor algorithm
---------------
This algorithm can be explained as preparing the main point and searching for the closest point to it,
then placing it in the second order,
then the algorithm re-searching for the point closest to the second point and placing it in the third order, and so on...

```php
     $getOrderByNearestNeighbor  = \DiamondDev\GeographicalCalculator\Facade\GeoFacade::setMainPoint([22, 37])
            ->setPoints([
                [33, 45],
                [31, 40],
            ])
            ->getOrderByNearestNeighbor();
     //  the result will be an array of points that you inserted before, and these points are ordered as we explain above.
     return $getOrderByNearestNeighbor;
```
