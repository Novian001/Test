import axios from "axios";

export const fetchPosts = async () => {
  const { data } = await axios.get(`${process.env.REACT_APP_API_HOST}/posts`);
  return data;
};

export const fetchPostById = async (id) => {
  const { data } = await axios.get(
    `${process.env.REACT_APP_API_HOST}/posts/${id}`
  );
  return data;
};

export const createPost = async (postData) => {
  const { data } = await axios.post(
    `${process.env.REACT_APP_API_HOST}/posts`,
    postData
  );
  return data;
};

export const updatePost = async (id, postData) => {
  const { data } = await axios.put(
    `${process.env.REACT_APP_API_HOST}/posts/${id}`,
    postData
  );
  return data;
};

export const deletePost = async (id) => {
  const { data } = await axios.delete(
    `${process.env.REACT_APP_API_HOST}/posts/${id}`
  );
  return data;
};
