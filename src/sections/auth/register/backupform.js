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
  // const [error, setError] = useState('');
  const [errorUsername, setErrorUsername] = useState('');
  const [errorEmail, setErrorEmail] = useState('');
  const [errorPassword, setErrorPassword] = useState('');
  const [errorKonfirmasi, setErrorKonfirmasi] = useState('');

  const salt = CryptoJS.lib.WordArray.random(128 / 8);
  const hashedPassword = CryptoJS.PBKDF2(password, salt, {
    keySize: 256 / 32,
    iterations: 1000,
  }).toString();
  const hashedPasswordConfirm = CryptoJS.PBKDF2(Konfirmasi, salt, {
    keySize: 256 / 32,
    iterations: 1000,
  }).toString();

  const [showPassword, setShowPassword] = useState(false);
  const [showPasswordConfirm, setShowPasswordConfirm] = useState(false);
  const saveUsers = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost:8080/Auth', {
        register_username: Username,
        register_email: email,
        register_password: password,
        register_confirmation_password: Konfirmasi,
      });
      Swal.fire({
        title: 'Berhasil',
        text: 'Anda berhasil mendaftar',
        icon: 'success',
        confirmButtonText: 'Ok',
      });
      navigate('/login');
      if (!response.ok) {
        const errorData = await response.json();
        setErrorUsername(errorData.response.data.messages.register_username);
        setErrorEmail(errorData.response.data.messages.register_email);
        setErrorPassword(errorData.response.data.messages.register_password);
        setErrorKonfirmasi(errorData.response.data.messages.register_confirmation_password);
      } else {
        Swal.fire({
          title: 'Berhasil',
          text: 'Anda berhasil mendaftar',
          icon: 'success',
          confirmButtonText: 'Ok',
        });
        navigate('/');
      }
    } catch (error) {
      console.log(error.response.data.messages);
      setErrorUsername(error.response.data.messages.register_username);
      setErrorEmail(error.response.data.messages.register_email);
      setErrorPassword(error.response.data.messages.register_password);
      setErrorKonfirmasi(error.response.data.messages.register_confirmation_password);
    }
  };
  return (
    <>
      <form onSubmit={saveUsers}>
        <Stack spacing={3}>
          <TextField
            error={errorUsername}
            helperText={errorUsername}
            // {error.}
            name="username"
            label="Username anda "
            onChange={(e) => setUsername(e.target.value)}
            type="text"
          />
          <TextField
            error={errorEmail}
            helperText={errorEmail}
            name="email"
            type="email"
            label="Email address"
            onChange={(e) => setemail(e.target.value)}
          />

          <TextField
            error={errorPassword}
            helperText={errorPassword}
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
            error={errorKonfirmasi}
            helperText={errorKonfirmasi}
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
        {/* {error && <p>{error}</p>} */}
      </form>
    </>
  );
}
