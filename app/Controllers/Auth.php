<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Auth extends ResourceController
{
    use ResponseTrait;
    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->authModel = new AuthModel();
        $this->userModel = new UserModel();
        $request = \Config\Services::request();
        $this->builder = $db->table('daftaruser');
        $validation = \Config\Services::validation();
        $session = \Config\Services::session();
    }

    public function index()
    {

        $dataUser = $this->authModel->findAll();
        return $this->respond($dataUser, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $dataUser = $this->authModel->find(['id_user' => $id]);
        if (!$dataUser) {
            return $this->failNotFound('Data tidak ditemukan');
        } else {
            return $this->respond($dataUser, 200);
        }
        return $this->respond($dataUser[0], 200);
    }



    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
    }
    // verfikasi email yang dikirim

    public function verifikasi()
    {
        $email = $this->request->getGet('email');
        $decodedHash = base64_decode($email); // mengubah hash dari format Base64 menjadi string
        // menghitung hash MD5 dari string
        $check_email = $this->builder->getWhere(['mail_user' => $decodedHash])->getRowArray();
        if ($check_email) {
            $data = [
                'user_aktif' => 1,
            ];
            $this->builder->where('mail_user', $decodedHash);
            $this->builder->update($data);
            return $this->respond(['status' => true, 'message' => 'Email berhasil diverifikasi']);
        } else {
            return $this->respond(['status' => false, 'message' => 'Email tidak ditemukan oleh server']);
        }
    }
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper(['form']);
        $rules = [
            'register_username' => [
                'rules' => 'required|min_length[3]|max_length[20]|is_unique[daftaruser.username_user.id,{id}]',
                'errors' => [
                    'required' => 'Username wajib di isi',
                    'min_length[3]' => 'Username terlalu pendek',
                    'max_length[20]' => 'Username terlalu panjang',
                    'is_unique' => 'Username sudah ada, silahkan gunakan yang lain',

                ],
            ],
            'register_email' => [
                'rules' => 'required|min_length[3]|is_unique[daftaruser.mail_user.id,{id}]',
                'errors' => [
                    'required' => 'Email wajib di isi',
                    'min_length[3]' => 'Email terlalu pendek',
                    'is_unique' => 'Email sudah ada,silahkan gunakan yang lain',
                ],
            ],
            'register_password' => [
                'rules' => 'required|trim|min_length[3]|regex_match[/^(?=.*[A-Z])(?=.*[0-9]).+$/]|trim',
                'errors' => [
                    'required' => 'Password wajib di isi',
                    'min_length[3]' => 'Password terlalu pendek',
                    'regex_match' => 'Password harus mengandung huruf besar dan angka',
                ],
            ],

            'register_confirmation_password' => [
                'rules' => 'required|trim|min_length[3]|matches[register_password]|regex_match[/^(?=.*[A-Z])(?=.*[0-9]).+$/]|trim',
                'errors' => [
                    'required' => 'Password konfirmasi wajib di isi',
                    'min_length[3]' => 'Password terlalu pendek',
                    'matches' => 'Password tidak sama,tolong samakan password yang dibuat dengan password konfirmasi',
                    'regex_match' => 'Password harus mengandung huruf besar dan angka',
                ],
            ],
        ];

        $dataRegister = [
            'nama_lengkap_user' => "nama belum di isi",
            'username_user' => $this->request->getVar('register_username'),
            'mail_user' => $this->request->getVar('register_email'),
            'password_user' =>  password_hash($this->request->getVar('register_password'), PASSWORD_BCRYPT, ['cost' => 16]),
            'jenisKelamin' => "belum di isi",
            'umur_user' => 0,
            'akun_dibuat' => date('Y-m-d H:i:s'),
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
        $this->authModel->save($dataRegister);
        $response =
            [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data dengan username ' . $this->request->getVar('register_username') . ' berhasil ditambahkan',
                ]
            ];
        return $this->respondCreated($response);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        helper(['form']);
        $rules = [
            'update_nama' => [
                'rules' => 'required|min_length[3]|max_length[20]',
                'errors' => [
                    'required' => 'Nama wajib di isi',
                    'min_length[3]' => 'Nama terlalu pendek',
                    'max_length[25]' => 'Nama terlalu panjang',
                ],
            ],
            'update_username' => [
                'rules' => 'required|min_length[3]|max_length[20]',
                'errors' => [
                    'required' => 'Username wajib di isi',
                    'min_length[3]' => 'Username terlalu pendek',
                    'max_length[20]' => 'Username terlalu panjang',
                    // 'is_unique' => 'Username sudah ada, silahkan gunakan yang lain',

                ],
            ],
            'update_email' => [
                'rules' => 'required|min_length[3]|is_unique[daftaruser.mail_user.id,{id}]',
                'errors' => [
                    'required' => 'Email wajib di isi',
                    'min_length[3]' => 'Email terlalu pendek',
                    // 'is_unique' => 'Email sudah ada,silahkan gunakan email yang lain',
                ],
            ],
            'update_password' => [
                'rules' => 'required|trim|min_length[3]',
                'errors' => [
                    'required' => 'Password wajib di isi',
                    'min_length[3]' => 'Password terlalu pendek',

                ],
            ],

            'update_confirmation_password' => [
                'rules' => 'required|trim|min_length[3]|matches[update_password]',
                'errors' => [
                    'required' => 'Password konfirmasi wajib di isi',
                    'min_length[3]' => 'Password terlalu pendek',
                    'matches' => 'Password tidak sama,tolong samakan password yang dibuat dengan password konfirmasi',
                ],
            ],
            'update_jeniskelamin' => [
                'rules' => 'required|trim|min_length[3]',
                'errors' => [
                    'required' => 'Jenis Kelamin wajib di isi',
                ],
            ],
            'update_umurUser' => [
                'rules' => 'required|trim',
                'errors' => [
                    'required' => 'Umur Kamu wajib di isi',

                ],
            ],

        ];
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
        if (!$this->authModel->find(['id_user' => $id])) {
            return $this->failNotFound('Data dengan id ' . $id . ' tidak ditemukan');
        }
        if ($this->request->getVar('update_username')) {
            $dataEdit = [
                'nama_lengkap_user' => $this->request->getVar('update_nama'),
                'username_user' => $this->request->getVar('update_username'),
                'mail_user' => $this->request->getVar('update_email'),
                'password_user' => $this->request->getVar('update_password'),
                'jenisKelamin' => $this->request->getVar('update_jeniskelamin'),
                'umur_user' => $this->request->getVar('update_umurUser'),
                'terakhir_diedit' => date('Y-m-d H:i:s'),
            ];
        }

        $this->authModel->update($id, $dataEdit);
        $response =
            [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data dengan username ' . $dataEdit['update_username'] . ' berhasil diubah',
                ]
            ];
        return $this->respond($response);
    }
    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if (!$this->authModel->find(['id_user' => $id])) {
            return $this->failNotFound('Data dengan id ' . $id . ' tidak ditemukan');
        }
        $this->authModel->delete($id);
        $this->userModel->delete($id);
        $response =
            [
                'status' => 200,
                'messages' => [
                    'success' => 'Data dengan id ' . $id . ' berhasil dihapus',
                ]
            ];
        return $this->respondDeleted($response);
    }
}
