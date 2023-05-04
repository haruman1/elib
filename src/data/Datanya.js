import axios from 'axios';
import { useEffect, useState } from 'react';

export default function useGetUsers() {
  const [users, setUsers] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      const response = await axios.get('http://localhost:8080/User/Joinan');
      setUsers(response.data.response);
      console.log(response.data.response);
    };
    fetchData();
  }, []);

  return users;
}
