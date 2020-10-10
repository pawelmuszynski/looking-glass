<?php

/*
 * Looking Glass - An easy to deploy Looking Glass
 * Copyright (C) 2017-2020 Guillaume Mazoyer <gmazoyer@gravitons.in>
 * Copyright (C) 2017-2020 Denis Fondras <github@ggl.ledeuns.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 */

require_once('unix.php');
require_once('includes/command_builder.php');
require_once('includes/utils.php');

final class OpenWRT extends UNIX {

  public function __construct($global_config, $config, $id, $requester) {
    parent::__construct($global_config, $config, $id, $requester);

    // Check if we need sudo or dosu
    if (isset($this->config['become_method']) && $this->config['become_method'] == 'doas') {
      $this->wrapper = 'doas bgpctl';
    } elseif (isset($this->config['become_method']) && $this->config['become_method'] == 'sudo') {
      $this->wrapper = 'sudo ip';
    } else {
      $this->wrapper = 'ip';
    }
  }

  protected function build_bgp($parameter) {
    if (!is_valid_ip_address($parameter)) {
      throw new Exception('The parameter is not an IP address.');
    }

    $cmd = new CommandBuilder();

    $cmd->add("ip");

    if (match_ipv6($parameter, false)) {
      $cmd->add('-6');
    }
    $cmd->add('route');
    $cmd->add('get', quote($parameter));

    return array($cmd);
  }

  protected function build_aspath_regexp($parameter) {
    $commands = array();
    return $commands;
  }

  protected function build_as($parameter) {
    $commands = array();
    return $commands;
  }
}

// End of openwrt.php
