import { useState, useEffect } from 'react';
import axios from 'axios';

const useGetUsers = () => {
  const [users, setUsers] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      const response = await axios.get('http://localhost:8080/User/Joinan');
      setUsers(response.data.response);
    };
    fetchData();
  }, []);

  return users;
};

const Users = () => {
  const usersData = useGetUsers();

  const ListUser = usersData.map((user, index) => ({
    id: index + 1,
    UserID: user.user_id,
    avatarURL: `http://ucarecdn.com/${user.avatarURL}`,
    Nama: user.nama_lengkap_user,
    Email: user.mail_user,
    JenisKelamin: user.jenisKelamin,
    isVerified: user.user_aktif,
    role: user.role_user,
    isOnline: user.user_login,
  }));
};

export default Users;
