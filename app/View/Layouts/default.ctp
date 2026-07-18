<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
//$cakeDescription = __d('cake_dev', 'Tool');
//$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
                Easy Logics
        </title>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('cake.generic');
        echo $this->Html->css('menu');
        echo $this->Html->css('jquery.datetimepicker');
        echo $this->Html->script('jquery-1.11.1.min');
        echo $this->Html->script('jquery.datetimepicker');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body  class="graybg">
        <div id="container">
            <div id="header">
                <div class="headercont">
                    <a href="" class="logo"></a>
                    <div class="header_title">Elogistics</div>
                    <div class="logout_cont">
                            <?php if ($this->Session->read('Auth.User')){ ?>
                        <div class="left"></div>
                        <div class="mid">
                            <div class="username">Signed in as <br/><span><?php echo AuthComponent::user('firstname') ?></span></div>
                            <div class="logout_divider"></div>
                            <div style="float:left">

								<?php echo $this->Html->link(__('Change Password'), array('controller' => 'Users', 'action' => 'change_password'),array('class'=>'changepaswd','style'=>' background: linear-gradient(center top , rgb(164, 212, 249), rgb(129, 196, 250)) repeat scroll 0 0 rgb(164, 212, 249);
                                                    background-color:rgb(164, 212, 249);font-weight:lighter;font-size:13px;padding:1px 9px;
                                                     border: 1px solid rgb(100, 180, 245); border-radius: 4px;
                                                     -webkit-gradient(linear, left top, left bottom, from(#a4d4f9), to(#81c4fa));')); ?>
                                <?php echo $this->Html->link(__('Logout'), array('controller' => 'Users', 'action' => 'logout'),
                                             array('style'=>' background:  linear-gradient(center top , rgb(164, 212, 249), rgb(129, 196, 250)) repeat scroll 0 0 rgb(164, 212, 249);
                                                     border: 1px solid rgb(100, 180, 245); border-radius: 4px;padding:0 40px;margin: 2px 0 0;
                                                     background-color:rgb(164, 212, 249);
                                                     -webkit-gradient(linear, left top, left bottom, from(#a4d4f9), to(#81c4fa));')); ?>
                            </div>
                        </div>
                        <div class="right"></div>
                            <?php } ?>
                    </div>
                </div>
            </div>
            <nav id="menu-wrap">
			<div id="clockbox"></div>
			<div id="clockboxTitle">Texas Time :&nbsp;&nbsp;</div>
			<div id="menu-trigger">Menu</div>
                <ul id="menu">
                    <?php
                    if(isset($menus['menu'])) {
                        $menuId = '';
                        foreach ($menus['menu'] as $mkey => $menu) { $menuId++; ?>
                            <li>
                                <?php
                                $menuLink = '#.';
                                if(isset($menu['link'])) {
                                    $menuLink = $menu['link'];
                                }
                                ?>
                                <a href="<?php echo $menuLink; ?>" onClick="selectMenu(<?php echo $menuId; ?>)" id="menu-<?php echo $menuId; ?>"><?php echo $mkey; ?></a>
                                    <ul>
                                    <?php
                                    if(isset($menu['submenu'])) {
                                        foreach ($menu['submenu'] as $smkey => $smvalue) {

                                            $submenuLink = '#.';
                                            if(isset($smkey)) {
                                                $submenuLink = $smkey;
                                            }
                                            ?>
                                            <li>
                                                <a href="<?php echo $submenuLink; ?>" onClick="selectMenu(<?php echo $menuId; ?>)"><?php echo $smvalue; ?></a>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </ul>
                            </li>
                        <?php
                        }
                    }
                    ?>
                </ul>
            </nav>
            <div id="content">
                <div class="block">
                    <?php echo $this->Session->flash(); ?>
                    <div class="breadcrumb">
                        <?php echo $this->Html->getCrumbs(' > ', 'Home');?>
                    </div>
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
            <div id="footer">
                <div class="footer_line"></div><div class="copyright"> &copy; <?php echo date('Y');?> Easy Logics Technology</div>
            </div>
            <script type="text/javascript">
                    function selectMenu(clkLink) {
                            $.ajax({
                                    url: "<?php echo $this->Html->url(array(
                                                    "controller" => "users",
                                                    "action" => "setMenu",
                                            ));?>?selected="+clkLink,
                                    cache: false,
                                    async: false,
                                    dataType: "html"
                              });
                            }
                            function selectSelectedMenu(data) {
                                    $(eval(data)).addClass( "selected" );
                            }
            </script>
            <?php
                    $this->Js->get('window')->event('load', $this->Js->request(
                            array(
                                    'controller' => 'users',
                                    'action' => 'getMenu'
                                    ), array(
                                    'success' => 'selectSelectedMenu(data)',
                                    'async' => true,
                                    'method' => 'get',
                                    'cache' => false,
                                    'dataExpression' => true,
                                    )
                            )
                    );
                    echo $this->Js->writeBuffer();
            ?>
        </div>
    <?php echo $this->element('sql_dump'); ?>
    </body>
</html>
