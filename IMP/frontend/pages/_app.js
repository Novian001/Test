import { ChakraProvider, extendTheme } from "@chakra-ui/react";

const theme = extendTheme({
  // extend the default theme here
});

function MyApp({ Component, pageProps }) {
  return (
    <ChakraProvider theme={theme}>
      <Component {...pageProps} />
    </ChakraProvider>
  );
}

export default MyApp;
