import { useQuery } from "react-query";
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
import axios from "axios";

const fetchPosts = async () => {
  const { data } = await axios.get("/api/posts");
  return data;
};

const Posts = () => {
  const { data: posts, isLoading } = useQuery("posts", fetchPosts);

  if (isLoading) {
    return <div>Loading...</div>;
  }

  return (
    <div>
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
                <Button colorScheme="blue" size="sm" mr={2}>
                  Edit
                </Button>
                <Button colorScheme="red" size="sm">
                  Delete
                </Button>
              </Td>
            </Tr>
          ))}
        </Tbody>
      </Table>
    </div>
  );
};

export default Posts;
