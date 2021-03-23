import axios from 'axios';

export default () => {
  
  const axiosinstance = axios.create({
    baseURL: 'https://app.expeditionguate.com/api/v1',
    timeout: 25000,
    headers: {
      // "Authorization": `Bearer ${localStorage.getItem('token')}`
    }
  })

  return axiosinstance;
}
