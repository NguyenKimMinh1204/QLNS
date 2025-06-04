 <aside class="main-sidebar">
     <!-- sidebar: style can be found in sidebar.less -->
     <section class="sidebar">
         <!-- Sidebar user panel -->
         <div class="user-panel">
             <div class="pull-left image">
                 <img src="../../assets/img/<?php echo $a->xuatavatarTheoId($user_id);?>" class="img-circle"
                     alt="User Image">
             </div>
             <div class="pull-left info">
                 <p><?php echo $a->xuatUsernameTheoId($user_id);?></p>
                 <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
             </div>

         </div>
         <!-- search form -->

         <!-- /.search form -->
         <!-- sidebar menu: : style can be found in sidebar.less -->
         <ul class="sidebar-menu">

             <li class="treeview">
                 <a href="department_management.php">
                     <i class="fa fa-laptop"></i>
                     <span>Quản lý phòng ban</span>

                 </a>

             </li>
             <li class="treeview">
                 <a href="User_management.php">
                     <i class="fa fa-laptop"></i>
                     <span>Quản lý tài khoản</span>

                 </a>

             </li>

             <li class="treeview">
                 <a href="#kpiManagement" data-toggle="collapse" aria-expanded="false">
                     <i class="fa fa-laptop"></i>
                     <span>Quản lý tên KPI</span>
                     <i class="fa fa-angle-left pull-right"></i>
                 </a>
                 <ul id="kpiManagement" class="collapse">
                     <li><a href="namekpi.php"><i class="fa fa-circle-o"></i> Tên KPI Phòng</a></li>
                     <li><a href="namekpi_emp.php"><i class="fa fa-circle-o"></i> Tên KPI Nhân Viên</a></li>
                 </ul>
             </li>

             <li class="treeview">
                 <a href="#kpiManagementMain" data-toggle="collapse" aria-expanded="false">
                     <i class="fa fa-laptop"></i>
                     <span>Quản lý KPI</span>
                     <i class="fa fa-angle-left pull-right"></i>
                 </a>
                 <ul id="kpiManagementMain" class="collapse">
                     <li><a href="quanlykpi_phong.php"><i class="fa fa-circle-o"></i> Quản lý KPI</a></li>
                     <li><a href="manager_kpi_bonus.php"><i class="fa fa-circle-o"></i> Quản lý thưởng KPI</a></li>
                 </ul>
             </li>
             <li class="treeview">
                 <a href="evaluetion.php">
                     <i class="fa fa-laptop"></i>
                     <span>Đánh giá KPI</span>

                 </a>

             </li>
             <li class="treeview">
                 <a href="quanlywifichamcong.php">
                     <i class="fa fa-laptop"></i>
                     <span>Quản lý địa chỉ wifi</span>

                 </a>

             </li>
             <li class="treeview">
                 <a href="timekeeping.php">
                     <i class="fa fa-laptop"></i>
                     <span>Dữ liệu công</span>

                 </a>

             </li>
             <li class="treeview">
                 <a href="duyetdon.php">
                     <i class="fa fa-laptop"></i>
                     <span>Duyệt Đơn</span>

                 </a>

             </li>
             <li class="treeview">
                 <a href="#tinhluong" data-toggle="collapse" aria-expanded="false">
                     <i class="fa fa-laptop"></i>
                     <span>Quản lý lương</span>
                     <i class="fa fa-angle-left pull-right"></i>
                 </a>
                 <ul id="tinhluong" class="collapse">
                     <li><a href="khoantru.php"><i class="fa fa-circle-o"></i> Tính khoản trừ</a></li>
                     <li><a href="manager_kpi_bonus.php"><i class="fa fa-circle-o"></i> Tính thưởng phòng</a>
                     </li>
                     <li><a href="thuongkpi_nv.php"><i class="fa fa-circle-o"></i> Tính thưởng nhân viên </a>
                     </li>
                     <li><a href="tinhluong.php"><i class="fa fa-circle-o"></i> Tính lương nhân viên </a>
                     </li>
                 </ul>
             </li>
             <!-- <li class="treeview">
                 <a href="#">
                     <i class="fa fa-files-o"></i>
                     <span>KPI</span>
                     <span class="label label-primary pull-right">4</span>
                 </a>
                 <ul class="treeview-menu">
                     <li><a href="KPI_dep.php"><i class="fa fa-circle-o"></i>KPI department</a>
                     </li>
                     <li><a href="#"><i class="fa fa-circle-o"></i>KPI personal</a></li>
                     <li><a href="#"><i class="fa fa-circle-o"></i>report</a></li>
                     <li><a href="#"><i class="fa fa-circle-o"></i>
                         </a></li>
                 </ul>
             </li> -->




         </ul>
     </section>
     <!-- /.sidebar -->
 </aside>