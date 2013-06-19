<!--
/**
 * COmanage Registry CO Group Member Select View
 *
 * Copyright (C) 2010-13 University Corporation for Advanced Internet Development, Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright     Copyright (C) 2011-13 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.1
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */
-->
<?php
  $params = array('title' => _txt('op.grm.edit', array($cur_co['Co']['name'], $co_group['CoGroup']['name'])));
  print $this->element("pageTitle", $params);

?>

<table id="co_people" class="ui-widget">
  <thead>
    <tr class="ui-widget-header">
      <th><?php print _txt('fd.name'); ?></th>
      <th><?php print _txt('fd.perms'); ?></th>
    </tr>
    <?php
      print $this->Form->create('CoGroupMember',
                                array('action' => 'updateGroup',
                                      'inputDefaults' => array('label' => false,
                                                               'div' => false))) . "\n";
      // beforeFilter needs CO ID
      print $this->Form->hidden('CoGroupMember.co_id', array('default' => $cur_co['Co']['id'])) . "\n";
      // Group ID must be global for isAuthorized
      print $this->Form->hidden('CoGroupMember.co_group_id', array('default' => $co_group['CoGroup']['id'])) . "\n";
    ?>
  </thead>
  
  <tbody>
    <?php $i = 0; ?>
    <?php foreach ($co_people as $p): ?>
    <tr class="line<?php print ($i % 2)+1; ?>">
      <td>
        <?php
          print $this->Html->link(Sanitize::html(generateCn($p['Name'])),
                                  array('controller' => 'co_people',
                                        'action' => 'edit',
                                        $p['CoPerson']['id'],
                                        'co' => $cur_co['Co']['id']));
        ?>
      </td>
      <td>
        <?php
          $isMember = isset($co_group_roles['members'][$p['CoPerson']['id']])
                      && $co_group_roles['members'][$p['CoPerson']['id']];
          $isOwner = isset($co_group_roles['owners'][$p['CoPerson']['id']])
                     && $co_group_roles['owners'][$p['CoPerson']['id']];
          $gmid = null;
          
          if($isMember) {
            $gmid = $co_group_roles['members'][$p['CoPerson']['id']];
          } elseif($isOwner) {
            $gmid = $co_group_roles['owners'][$p['CoPerson']['id']];
          }
          
          if($gmid) {
            print $this->Form->hidden('CoGroupMember.rows.'.$i.'.id',
                                      array('default' => $gmid)) . "\n";
          }
          print $this->Form->hidden('CoGroupMember.rows.'.$i.'.co_person_id',
                                   array('default' => $p['CoPerson']['id'])) . "\n";
          print $this->Form->checkbox('CoGroupMember.rows.'.$i.'.member',
                                      array('checked' => $isMember))
                . _txt('fd.group.mem') . "\n";
          print $this->Form->checkbox('CoGroupMember.rows.'.$i.'.owner',
                                      array('checked' => $isOwner))
                . _txt('fd.group.own') . "\n";
        ?>
      </td>
    </tr>
    <?php $i++; ?>
    <?php endforeach; ?>
  </tbody>
  
  <tfoot>
    <tr class="ui-widget-header">
      <th colspan="2">
      </th>
    </tr>
    <tr>
      <td>
        <?php
          print $this->Form->submit(_txt('op.save'));
          print $this->Form->end();
        ?>
      </td>
    </tr>
  </tfoot>
</table>
