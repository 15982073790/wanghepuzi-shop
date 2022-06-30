<?php

namespace CxtCloud\Course\Inneruse\V\CourseVideo;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method GetPlayVideos getplayvideos(array $options = [])  
 * @method Get_play_videoids get_play_videoids(array $options = [])  
 * @method VideoBelongToCourse videobelongtocourse(array $options = [])  
 * @method Video_belong_to_course video_belong_to_course(array $options = [])  
 * @method GetVideoIdsByCourse getvideoidsbycourse(array $options = [])  
 * @method Get_videoIds_by_courseids get_videoids_by_courseids(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
