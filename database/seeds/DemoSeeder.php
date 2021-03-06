<?php

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $demo = new \App\User;
        $demo->username = "super";
        $demo->name = "Superadmin Demo";
        $demo->email = "super@demo.com";
        $demo->roles = json_encode(["OWNER", "ADMIN"]);
        $demo->password = \Hash::make("mia12345");
        $demo->avatar = "";
        $demo->phone = "08123456789";
        $demo->address = "Pekanbaru, Riau";
        $demo->api_token= "asnnd12e1124141onon1e";
        $demo->device_token= "asnnd12e1234141onon1e";
        $demo->save();

        // $demo = new \App\User;
        // $demo->username = "demo";
        // $demo->name = "Superadmin Demo";
        // $demo->email = "demo@demo.com";
        // $demo->roles = json_encode(["OWNER", "ADMIN"]);
        // $demo->password = \Hash::make("demo12345");
        // $demo->avatar = "";
        // $demo->phone = "08123456789";
        // $demo->address = "Pekanbaru, Riau";
        // $demo->api_token= "asnnd12e1124141onon1e";
        // $demo->device_token= "asnnd12e1234141onon1e";
        // $demo->save();

        $demo = new \App\User;
        $demo->username = "admin";
        $demo->name = "Admin 1";
        $demo->email = "admin@demo.com";
        $demo->roles = json_encode(["OWNER"]);
        $demo->password = \Hash::make("admin12345");
        $demo->avatar = "";
        $demo->phone = "08123456789";
        $demo->address = "Pekanbaru, Riau";
        $demo->api_token= "asnnd12e1455141onon1e";
        $demo->device_token= "asnnd12e1411141onon1e";
        $demo->save();


        $demo = new \App\Gedung;
        $demo->nama_gedung = "Fakultas Teknik";
        $demo->kode_gedung = "FT-01";
        $demo->created_by = '1';
        $demo->save();


        $demo = new \App\Gedung;
        $demo->nama_gedung = "Fakultas Ekonomi";
        $demo->kode_gedung = "FEB-02";
        $demo->created_by = '1';
        $demo->save();


        $demo = new \App\Gedung;
        $demo->nama_gedung = "Fakultas Ilmu Politik dan Sosial";
        $demo->kode_gedung = "FISIP-03";
        $demo->created_by = '1';
        $demo->save();


        $demo = new \App\Gedung;
        $demo->nama_gedung = "Fakultas Pertanian";
        $demo->kode_gedung = "FAPERTA-04";
        $demo->created_by = '1';
        $demo->save();



        $demo = new \App\Traffic;
        $demo->perangkat_id = "2";
        $demo->nilai = "19";
        $demo->keterangan = "traffic";
        $demo->created_by = '1';
        $demo->save();


        $demo = new \App\Traffic;
        $demo->perangkat_id = "2";
        $demo->nilai = "25";
        $demo->keterangan = "traffic";
        $demo->created_by = '1';
        $demo->save();


        $demo = new \App\Traffic;
        $demo->perangkat_id = "2";
        $demo->nilai = "18";
        $demo->keterangan = "traffic";
        $demo->created_by = '1';
        $demo->save();


        $demo = new \App\Traffic;
        $demo->perangkat_id = "2";
        $demo->nilai = "30";
        $demo->keterangan = "traffic";
        $demo->created_by = '1';
        $demo->save();

        $demo = new \App\Traffic;
        $demo->perangkat_id = "2";
        $demo->nilai = "20";
        $demo->keterangan = "traffic";
        $demo->created_by = '1';
        $demo->save();



        $demo = new \App\Traffic;
        $demo->perangkat_id = "2";
        $demo->nilai = "40";
        $demo->keterangan = "traffic";
        $demo->created_by = '1';
        $demo->save();
        

    }
}
