<?php
namespace Hae\Core;

interface Entity
{
    function getId() : int;

    function toArray() : array;
}