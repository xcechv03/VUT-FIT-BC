-- fsm.vhd: Finite State Machine
-- Author(s): Tomáš Èechvala
--
library ieee;
use ieee.std_logic_1164.all;
-- ----------------------------------------------------------------------------
--                        Entity declaration
-- ----------------------------------------------------------------------------
entity fsm is
port(
   CLK         : in  std_logic;
   RESET       : in  std_logic;

   -- Input signals
   KEY         : in  std_logic_vector(15 downto 0);
   CNT_OF      : in  std_logic;

   -- Output signals
   FSM_CNT_CE  : out std_logic;
   FSM_MX_MEM  : out std_logic;
   FSM_MX_LCD  : out std_logic;
   FSM_LCD_WR  : out std_logic;
   FSM_LCD_CLR : out std_logic
);
end entity fsm;

-- ----------------------------------------------------------------------------
--                      Architecture declaration
-- ----------------------------------------------------------------------------
architecture behavioral of fsm is
   type t_state is (TEST_DEFAULT, TEST_14, TEST_146, TEST_146A, TEST_14626, TEST_146264, TEST_1462649, TEST_14626495, TEST_146264952, TEST_1462649528, TEST_14626495280, TEST_KOD, TEST_14639, TEST_146395, TEST_1463952, TEST_14639529, TEST_146395290, TEST_1463952900, TEST_14639529003, TEST_KOD2, PRINT_ERROR, TEST_ERROR, PRINT_OK, FINISH);
   signal present_state, next_state : t_state;

begin
-- -------------------------------------------------------
sync_logic : process(RESET, CLK)
begin
   if (RESET = '1') then
      present_state <= TEST_DEFAULT;
   elsif (CLK'event AND CLK = '1') then
      present_state <= next_state;
   end if;
end process sync_logic;

-- -------------------------------------------------------
next_state_logic : process(present_state, KEY, CNT_OF)
begin
   case (present_state) is
   -- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_DEFAULT =>
      next_state <= TEST_DEFAULT;
		if (KEY(1) = '1') then
			next_state <= TEST_14; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_14 =>
      next_state <= TEST_14;
		if (KEY(4) = '1') then
			next_state <= TEST_146; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
				-- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_146 =>
      next_state <= TEST_146;
		if (KEY(6) = '1') then
			next_state <= TEST_146A; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_146A =>
      next_state <= TEST_146A;
		if (KEY(2) = '1') then
			next_state <= TEST_14626; 
		elsif (KEY(3) = '1') then
			next_state <= TEST_14639; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
					-- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_14626 =>
      next_state <= TEST_14626;
		if (KEY(6) = '1') then
			next_state <= TEST_146264; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_146264 =>
      next_state <= TEST_146264;
		if (KEY(4) = '1') then
			next_state <= TEST_1462649; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_1462649 =>
      next_state <= TEST_1462649;
		if (KEY(9) = '1') then
			next_state <= TEST_14626495; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_14626495 =>
      next_state <= TEST_14626495;
		if (KEY(5) = '1') then
			next_state <= TEST_146264952; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
		when TEST_146264952 =>
      next_state <= TEST_146264952;
		if (KEY(2) = '1') then
			next_state <= TEST_1462649528; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
		when TEST_1462649528 =>
      next_state <= TEST_1462649528;
		if (KEY(8) = '1') then
			next_state <= TEST_14626495280; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
		when TEST_14626495280 =>
      next_state <= TEST_14626495280;
		if (KEY(0) = '1') then
			next_state <= TEST_KOD; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - - - - - - - - - - - - - - - - - - -
		  when TEST_KOD =>
      next_state <= TEST_KOD;
      if (KEY(15) = '1') then
         next_state <= PRINT_OK; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_14639 =>
      next_state <= TEST_14639;
		if (KEY(9) = '1') then
			next_state <= TEST_146395; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_146395 =>
      next_state <= TEST_146395;
		if (KEY(5) = '1') then
			next_state <= TEST_1463952; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_1463952 =>
      next_state <= TEST_1463952;
		if (KEY(2) = '1') then
			next_state <= TEST_14639529; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_14639529 =>
      next_state <= TEST_14639529;
		if (KEY(9) = '1') then
			next_state <= TEST_146395290; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_146395290 =>
      next_state <= TEST_146395290;
		if (KEY(0) = '1') then
			next_state <= TEST_1463952900; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_1463952900 =>
      next_state <= TEST_1463952900;
		if (KEY(0) = '1') then
			next_state <= TEST_14639529003; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - --- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_14639529003 =>
      next_state <= TEST_14639529003;
		if (KEY(3) = '1') then
			next_state <= TEST_KOD2; 
      elsif (KEY(15) = '1') then
         next_state <= PRINT_ERROR; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - - - - - - - - - - - - - - - - - - -
		  when TEST_KOD2 =>
      next_state <= TEST_KOD2;
      if (KEY(15) = '1') then
         next_state <= PRINT_OK; 
		elsif (KEY(14 downto 0) /= "000000000000000") then
         next_state <= TEST_ERROR; 
      end if;
		-- - - - - - - - - - - - - - - - - - - - - - -
   when TEST_ERROR =>
      next_state <= TEST_ERROR;
		if (KEY(15) = '1') then
         next_state <= PRINT_ERROR;
      end if;
   -- - - - - - - - - - - - - - - - - - - - - - -
   when PRINT_ERROR =>
      next_state <= PRINT_ERROR;
      if (CNT_OF = '1') then
         next_state <= FINISH;
      end if;
		-- - - - - - - - - - - - - - - - - - - - - - -
   when PRINT_OK =>
      next_state <= PRINT_OK;
      if (CNT_OF = '1') then
         next_state <= FINISH;
      end if;
   -- - - - - - - - - - - - - - - - - - - - - - -
   when FINISH =>
      next_state <= FINISH;
      if (KEY(15) = '1') then
         next_state <= TEST_DEFAULT; 
      end if;
   -- - - - - - - - - - - - - - - - - - - - - - -
   when others =>
      next_state <= TEST_DEFAULT;
   end case;
end process next_state_logic;

-- -------------------------------------------------------
output_logic : process(present_state, KEY)
begin
   FSM_CNT_CE     <= '0';
   FSM_MX_MEM     <= '0';
   FSM_MX_LCD     <= '0';
   FSM_LCD_WR     <= '0';
   FSM_LCD_CLR    <= '0';

   case (present_state) is
   -- - - - - - - - - - - - - - - - - - - - - - -
   -- - - - - - - - - - - - - - - - - - - - - - -
   when PRINT_ERROR =>
      FSM_CNT_CE     <= '1';
      FSM_MX_LCD     <= '1';
      FSM_LCD_WR     <= '1';
		-- - - - - - - - - - - - - - - - - - - - - - -
   when PRINT_OK =>
      FSM_CNT_CE     <= '1';
      FSM_MX_LCD     <= '1';
      FSM_LCD_WR     <= '1';
		FSM_MX_MEM     <= '1';
   -- - - - - - - - - - - - - - - - - - - - - - -
   when FINISH =>
      if (KEY(15) = '1') then
         FSM_LCD_CLR    <= '1';
      end if;
   -- - - - - - - - - - - - - - - - - - - - - - -
   when others =>
	 if (KEY(14 downto 0) /= "000000000000000") then
         FSM_LCD_WR     <= '1';
      end if;
      if (KEY(15) = '1') then
         FSM_LCD_CLR    <= '1';
      end if;
   end case;
end process output_logic;

end architecture behavioral;

