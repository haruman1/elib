import { useEffect, useState } from 'react';

const UserData = () => {
  const [tableData, setTableData] = useState([]);

  useEffect(() => {
    fetch('http://localhost:8080/User/Joinan')
      .then((data) => data.json())
      .then((data) => setTableData(data));
  }, []);
};

export default UserData;
