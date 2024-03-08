
import 'bootstrap/dist/css/bootstrap.min.css'
import { Button } from 'react-bootstrap'
import axiosClient from './axios-client'

function App() {

  const callApi = (e) => {
    e.preventDefault
    axiosClient.get('test-api')
    .then((res) => {
      console.log(res)
    })
    .catch((err) => {
      console.log(err)
    })
  }

  return (
    <>
      <Button variant='danger' onClick={callApi}>test</Button>
    </>
  )
}

export default App
