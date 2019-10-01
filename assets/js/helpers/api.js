import axios from 'axios'

const baseUrl = '/api';

const instance = axios.create({
  baseURL: baseUrl,
});

export const getFigures = batchId => {
  const url = `/figures/${batchId}`;

  return instance.get(url).then(data => data);
};

export const createFigures = () => {
  return instance.post('figures/generate').then(data => data);
};

export const changeFigures = batchId => {
  const url = `/figures/${batchId}/change`;

  return instance.post(url).then(data => data);
};

export const revertChanges = changesId => {
  const url = `/figure-changes/${changesId}/revert`;

  return instance.post(url).then(data => data);
};

export const getChanges = batchId => {
  const url = `/figures/${batchId}/changes`;

  return instance.get(url).then(data => data);
};

export default instance;
