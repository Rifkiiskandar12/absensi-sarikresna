<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->post('auth/proses_login', 'Auth::proses_login');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('auth/logout', 'Auth::logout');
$routes->post('absensi/proses_masuk', 'Absensi::proses_masuk');
$routes->post('absensi/proses_keluar', 'Absensi::proses_keluar');
$routes->post('hrd/simpan_karyawan', 'Hrd::simpan_karyawan');
$routes->post('cuti/ajukan', 'Cuti::ajukan');
$routes->get('cuti/setuju/(:num)', 'Cuti::setuju/$1');
$routes->get('cuti/tolak/(:num)', 'Cuti::tolak/$1');
$routes->get('admin/toggle_status/(:num)/(:num)', 'Admin::toggle_status/$1/$2');
$routes->get('admin/reset_karyawan/(:num)', 'Admin::reset_karyawan/$1');
$routes->get('admin/reset_manajemen/(:num)', 'Admin::reset_manajemen/$1');
$routes->post('admin/simpan_karyawan', 'Admin::simpan_karyawan');