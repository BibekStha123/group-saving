import React, { useState } from 'react';
import { Button, Form } from 'react-bootstrap';
import axiosClient from './axios-client'

function Register() {

    const [user, setUser] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
    })

    const onSubmit = (e) => {
        e.preventDefault
        axiosClient.post('/register', user)
            .then((res) => {
                console.log(res)
            })
            .catch((error) => {
                console.log(error)
            })
    }

    return (
        <Form>
            <Form.Group className="mb-3" controlId="">
                <Form.Label>Name</Form.Label>
                <Form.Control type="text" placeholder="Enter Name" onChange={e => setUser({ ...user, name: e.target.value })} />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formBasicEmail">
                <Form.Label>Email address</Form.Label>
                <Form.Control type="email" placeholder="Enter email" onChange={e => setUser({ ...user, email: e.target.value })} />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formBasicPassword">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" placeholder="Password" onChange={e => setUser({ ...user, password: e.target.value })} />
            </Form.Group>
            <Form.Group className="mb-3" controlId="formBasicPassword">
                <Form.Label>Password Confirmation</Form.Label>
                <Form.Control type="password" placeholder="Password" onChange={e => setUser({ ...user, password_confirmation: e.target.value })} />
            </Form.Group>
            <Button variant="primary" onClick={onSubmit}>
                Submit
            </Button>
        </Form>
    );
}

export default Register;