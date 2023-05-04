import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import CryptoJS from 'crypto-js';
import Swal from 'sweetalert2';
// @mui
import { Link, Stack, IconButton, InputAdornment, TextField } from '@mui/material';
import { LoadingButton } from '@mui/lab';
// components
import Iconify from '../../../components/iconify';

// ----------------------------------------------------------------------

export default function RegisterForm() {
  const navigate = useNavigate();
  const [Username, setUsername] = useState('');
  const [email, setemail] = useState('');
  // password only
  const [password, setPassword] = useState('');
  const [Konfirmasi, setKonfirmasi] = useState('');
  // semua error
  const [errorData, setErrorData] = useState('');

  const [showPassword, setShowPassword] = useState(false);
  const [showPasswordConfirm, setShowPasswordConfirm] = useState(false);

  const saveUsers = async (e) => {
    if (password === Konfirmasi) {
      e.preventDefault();
      await axios
        .post('http://localhost:8080/Auth', {
          register_username: Username,
          register_email: email,
          register_password: password,
          register_confirmation_password: Konfirmasi,
        })
        .then(() => {
          Swal.fire({
            title: 'Berhasil',
            text: 'akun berhasil dibuat, Silahkan check email anda untuk aktivasi',
            icon: 'success',
            confirmButtonText: 'Ok',
          });
          navigate('/login');
        })
        .catch((error) => {
          console.log(error.response);
          setErrorData(error.response.data.messages);
        });
    } else {
      Swal.fire({
        title: 'Gagal',
        text: 'Password tidak sama',
        icon: 'error',
        confirmButtonText: 'Ok',
      });
    }
  };
  return (
    <>
      <form onSubmit={saveUsers}>
        <Stack spacing={3}>
          <TextField
            id="outlined-error-helper-text"
            label="Error"
            defaultValue="Hello World"
            helperText="Incorrect entry."
          />
          <TextField
            error={!!errorData.register_username}
            helperText={errorData.register_username}
            name="username"
            label="Username anda"
            onChange={(e) => setUsername(e.target.value)}
            type="text"
          />
          <TextField
            name="email"
            error={!!errorData.register_email}
            helperText={errorData.register_email}
            type="email"
            label="Email address"
            onChange={(e) => setemail(e.target.value)}
          />

          <TextField
            error={!!errorData.register_password}
            helperText={errorData.register_password}
            name="password"
            label="Password"
            onChange={(e) => setPassword(e.target.value)}
            type={showPassword ? 'text' : 'password'}
            InputProps={{
              endAdornment: (
                <InputAdornment position="end">
                  <IconButton onClick={() => setShowPassword(!showPassword)} edge="end">
                    <Iconify icon={showPassword ? 'eva:eye-fill' : 'eva:eye-off-fill'} />
                  </IconButton>
                </InputAdornment>
              ),
            }}
          />
          <TextField
            error={!!errorData.register_confirmation_password}
            helperText={errorData.register_confirmation_password}
            name="konfirmasi_password"
            label="Password"
            onChange={(e) => setKonfirmasi(e.target.value)}
            type={showPasswordConfirm ? 'text' : 'password'}
            InputProps={{
              endAdornment: (
                <InputAdornment position="end">
                  <IconButton onClick={() => setShowPasswordConfirm(!showPasswordConfirm)} edge="end">
                    <Iconify icon={showPasswordConfirm ? 'eva:eye-fill' : 'eva:eye-off-fill'} />
                  </IconButton>
                </InputAdornment>
              ),
            }}
          />
        </Stack>

        <Stack direction="row" alignItems="center" justifyContent="space-between" sx={{ my: 2 }}>
          <Link variant="subtitle2" href="forget" underline="hover">
            Forgot password?
          </Link>
        </Stack>

        <LoadingButton fullWidth size="large" type="submit" variant="contained">
          Register
        </LoadingButton>
      </form>
    </>
  );
}
