import { Routes, Route, Link } from 'react-router-dom';
import { NavigationMenu, NavigationMenuList, NavigationMenuItem, NavigationMenuTrigger, NavigationMenuContent, NavigationMenuLink } from "@/components/ui/navigation-menu"
import { ThemeProvider } from './components/theme-provider';
import { ModeToggle } from './components/mode-toggle';

// Components
import LoginPage from './app/login/page';
import SignupPage from './app/signup/signup';

function App() {
  return (
    <div>
       <ThemeProvider defaultTheme='dark' storageKey='vite-ui-theme'>
      <div className='flex justify-between p-4'>
        <NavigationMenu>
          <NavigationMenuList>
            <NavigationMenuItem>
              <NavigationMenuTrigger>Item One</NavigationMenuTrigger>
              <NavigationMenuContent>
                <NavigationMenuLink>Link</NavigationMenuLink>
              </NavigationMenuContent>
            </NavigationMenuItem>
          </NavigationMenuList>
        </NavigationMenu>
        <ModeToggle />
      </div>
      

        <Routes>
          <Route path="/" element={<LoginPage />} />
          <Route path='/signup' element={<SignupPage />} />
        </Routes>
      </ThemeProvider>
    </div>
  );
}

export default App;
