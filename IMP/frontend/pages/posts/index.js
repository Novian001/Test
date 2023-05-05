import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "react-query";
import {
  Heading,
  Table,
  Thead,
  Tbody,
  Tr,
  Th,
  Td,
  Button,
} from "@chakra-ui/react";
import { useForm } from "react-hook-form";
import {
  fetchPosts,
  fetchPostById,
  createPost,
  updatePost,
  deletePost,
} from "../api/hello";
import { reset } from "react-hook-form";

const Posts = () => {
  const { data: posts, isLoading } = useQuery("posts", fetchPosts);
  const queryClient = useQueryClient();
  const { register, handleSubmit, reset } = useForm();
  const [selectedPost, setSelectedPost] = useState(null);

  const {
    isLoading: isCreating,
    isError: createError,
    mutateAsync: mutateCreatePost,
  } = useMutation(createPost, {
    onSuccess: () => {
      queryClient.invalidateQueries("posts");
      reset();
    },
  });

  const {
    isLoading: isUpdating,
    isError: updateError,
    mutateAsync: mutateUpdatePost,
  } = useMutation(updatePost, {
    onSuccess: () => {
      queryClient.invalidateQueries("posts");
      setSelectedPost(null);
      reset();
    },
  });

  const {
    isLoading: isDeleting,
    isError: deleteError,
    mutateAsync: mutateDeletePost,
  } = useMutation(deletePost, {
    onSuccess: () => {
      queryClient.invalidateQueries("posts");
    },
  });

  const handleEditClick = (post) => {
    setSelectedPost(post);
    reset({ title: post.title, body: post.body });
  };

  const handleDeleteClick = async (id) => {
    if (window.confirm("Are you sure you want to delete this post?")) {
      await mutateDeletePost(id);
    }
  };

  const handleFormSubmit = async (formData) => {
    if (selectedPost) {
      await mutateUpdatePost({ ...selectedPost, ...formData });
    } else {
      await mutateCreatePost(formData);
    }
  };
  return (
    <QueryClientProvider client={queryClient}>
      <Heading>Posts</Heading>
      <Table>
        <Thead>
          <Tr>
            <Th>Title</Th>
            <Th>Actions</Th>
          </Tr>
        </Thead>
        <Tbody>
          {posts.map((post) => (
            <Tr key={post.id}>
              <Td>{post.title}</Td>
              <Td>
                <Button
                  colorScheme="blue"
                  size="sm"
                  mr={2}
                  onClick={() => handleEditClick(post)}
                >
                  Edit
                </Button>
                <Button
                  colorScheme="red"
                  size="sm"
                  onClick={() => handleDeleteClick(post.id)}
                  disabled={isDeleting}
                >
                  Delete
                </Button>
              </Td>
            </Tr>
          ))}
        </Tbody>
      </Table>
      <form onSubmit={handleSubmit(handleFormSubmit)}>
        <input type="hidden" {...register("id")} />
        <div>
          <label>Title</label>
          <input type="text" {...register("title", { required: true })} />
          {errors.title && <p>Title is required</p>}
        </div>
        <div>
          <label>Content</label>
          <textarea {...register("content", { required: true })} />
          {errors.content && <p>Content is required</p>}
        </div>
        <Button type="submit" disabled={isLoading}>
          {isLoading ? "Submitting..." : "Submit"}
        </Button>
      </form>
    </QueryClientProvider>
  );
};

export default Posts;
