import React, { useState } from 'react';
import { Button, Form } from 'react-bootstrap'
import axiosClient from './axios-client';
import { useNavigate } from 'react-router-dom';


function Login() {

    const navigate = useNavigate();

    const [user, setUser] = useState({
        email: '',
        password: '',
    })

    const onSubmit = (e) => {
        e.preventDefault
        axiosClient.post('/login', user)
            .then(({ data }) => {
                if (data.token) {
                    localStorage.setItem('TOKEN', data.token)
                    if (!data.user.is_admin) {
                        navigate('/dashboard')
                    } else {
                        navigate('/admin-dashboard')
                    }
                }
            })
            .catch((error) => {
                console.log(error)
            })
    }

    return (
        <Form>
            <Form.Group className="mb-3" controlId="formBasicEmail" >
                <Form.Label>Email address</Form.Label>
                <Form.Control type="email" placeholder="Enter email" onChange={e => setUser({ ...user, email: e.target.value })} />
            </Form.Group>

            <Form.Group className="mb-3" controlId="formBasicPassword">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" placeholder="Password" onChange={e => setUser({ ...user, password: e.target.value })} />
            </Form.Group>
            <Button variant="primary" onClick={onSubmit}>
                Submit
            </Button>
        </Form>
    );
}

export default Login;