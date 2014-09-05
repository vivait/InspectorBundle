<?php
/*
 * Based upon SoclozEventQueueBundle
 * Copyright CloseToMe 2011/2012
 * Released under the The MIT License
 */

namespace Vivait\InspectorBundle\Serializer;

/**
 * Interface for serializers
 *
 * @author jfb
 * @package SoclozEventQueueBundle
 */
interface SerializerInterface
{

    public function serialize($entity);

    public function deserialize($data);

}