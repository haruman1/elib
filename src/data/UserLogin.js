import axios from 'axios';
import { useEffect, useState } from 'react';

export default function UserLogin() {
  const [username, setUsername] = useState([]);

  useEffect(() => {
    fetch('http://localhost:8080/User/Log')
      .then((data) => data.json())
      .then((data) => setUsername(data));
  }, []);

  return username;
}
