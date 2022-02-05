-- cpu.vhd: Simple 8-bit CPU (BrainLove interpreter)
-- Copyright (C) 2021 Brno University of Technology,
--                    Faculty of Information Technology
-- Author(s): xcechv03
--

library ieee;
use ieee.std_logic_1164.all;
use ieee.std_logic_arith.all;
use ieee.std_logic_unsigned.all;

-- ----------------------------------------------------------------------------
--                        Entity declaration
-- ----------------------------------------------------------------------------
entity cpu is
 port (
   CLK   : in std_logic;  -- hodinovy signal
   RESET : in std_logic;  -- asynchronni reset procesoru
   EN    : in std_logic;  -- povoleni cinnosti procesoru
 
   -- synchronni pamet ROM
   CODE_ADDR : out std_logic_vector(11 downto 0); -- adresa do pameti
   CODE_DATA : in std_logic_vector(7 downto 0);   -- CODE_DATA <- rom[CODE_ADDR] pokud CODE_EN='1'
   CODE_EN   : out std_logic;                     -- povoleni cinnosti
   
   -- synchronni pamet RAM
   DATA_ADDR  : out std_logic_vector(9 downto 0); -- adresa do pameti
   DATA_WDATA : out std_logic_vector(7 downto 0); -- ram[DATA_ADDR] <- DATA_WDATA pokud DATA_EN='1'
   DATA_RDATA : in std_logic_vector(7 downto 0);  -- DATA_RDATA <- ram[DATA_ADDR] pokud DATA_EN='1'
   DATA_WREN  : out std_logic;                    -- cteni z pameti (DATA_WREN='0') / zapis do pameti (DATA_WREN='1')
   DATA_EN    : out std_logic;                    -- povoleni cinnosti
   
   -- vstupni port
   IN_DATA   : in std_logic_vector(7 downto 0);   -- IN_DATA obsahuje stisknuty znak klavesnice pokud IN_VLD='1' a IN_REQ='1'
   IN_VLD    : in std_logic;                      -- data platna pokud IN_VLD='1'
   IN_REQ    : out std_logic;                     -- pozadavek na vstup dat z klavesnice
   
   -- vystupni port
   OUT_DATA : out  std_logic_vector(7 downto 0);  -- zapisovana data
   OUT_BUSY : in std_logic;                       -- pokud OUT_BUSY='1', LCD je zaneprazdnen, nelze zapisovat,  OUT_WREN musi byt '0'
   OUT_WREN : out std_logic                       -- LCD <- OUT_DATA pokud OUT_WE='1' a OUT_BUSY='0'
 );
end cpu;


-- ----------------------------------------------------------------------------
--                      Architecture declaration
-- ----------------------------------------------------------------------------
architecture behavioral of cpu is

---- PC ----
	signal pc_register: std_logic_vector(11 downto 0);
	signal pc_increase: std_logic;
	signal pc_decrease: std_logic;
	
---- PTR ----
	signal ptr_register: std_logic_vector(9 downto 0);
	signal ptr_increase: std_logic;
	signal ptr_decrease: std_logic;

---- COUNTER ----	
	signal cnt_register: std_logic_vector(7 downto 0);
	signal cnt_increase: std_logic;
	signal cnt_decrease: std_logic;

---- MULTIPLEXOR ----
	signal mx_select: std_logic_vector(1 downto 0);

	
---- FSM ----
	type fsm_stav is (
		state_fetch,
		state_decode,
		
		state_ptr_increase, 
		state_ptr_decrease,
		
		state_var_increase, 
		state_var_increase_2, 
		state_var_decrease, 
		state_var_decrease_2,
		
		state_write, 
		state_write_2, 
		
		state_get, 
		state_null, 
		
		state_while_start, 
		state_while_start_2, 
		state_while_start_3, 
		state_while_start_4,
		state_while_end, 
		state_while_end_2, 
		state_while_end_3, 
		state_while_end_4, 
		state_while_end_5, 
		state_while_end_6,
		state_break,
		state_break_2,
		state_break_3

	);

---- FSM STAVY ----
	signal state: fsm_stav;
	signal nstate: fsm_stav;

begin


	mx: process(IN_DATA, DATA_RDATA, mx_select) is
	begin

		case(mx_select) is
			when "00" => 
				DATA_WDATA <= IN_DATA;
			when "01" => 
				DATA_WDATA <= DATA_RDATA + 1;
			when "10" => 
				DATA_WDATA <= DATA_RDATA - 1;
			when others => 
				DATA_WDATA <= (others => '0');
		end case;

	end process mx;

	pc: process(CLK, RESET, pc_register, pc_increase, pc_decrease) is
	begin
		
		if(RESET = '1') then
			pc_register <= (others => '0');
		elsif rising_edge(CLK) then
			if(pc_increase = '1') then
				pc_register <= pc_register + 1;
			elsif(pc_decrease = '1') then
				pc_register <= pc_register - 1;
			end if;
		end if;

	end process pc;
	
	CODE_ADDR <= pc_register;

	

	ptr: process(CLK, RESET, ptr_register, ptr_increase, ptr_decrease) is
	begin

		if(RESET = '1') then
			ptr_register <= (others => '0');
		elsif rising_edge(CLK) then
			if(ptr_increase = '1') then
				ptr_register <= ptr_register + 1;
			elsif(ptr_decrease = '1') then
				ptr_register <= ptr_register - 1;
			end if;
		end if;

	end process ptr;
	
	DATA_ADDR <= ptr_register;
	

	cnt: process(CLK, RESET, cnt_increase, cnt_decrease) is
	begin 
		
		if(RESET = '1') then
			cnt_register <= (others => '0');
		elsif rising_edge(CLK) then
			if(cnt_increase = '1') then
				cnt_register <= cnt_register + 1;
			elsif(cnt_decrease = '1') then
				cnt_register <= cnt_register - 1;
			end if;
		end if;	

	end process cnt;

	stav_logic: process(CLK, RESET) is
	begin

		if(RESET = '1') then
			state <= state_fetch;
		elsif rising_edge(CLK) then
				state <= nstate;
			
		end if;

	end process stav_logic;

	next_state: process(cnt_register, state, DATA_RDATA, CODE_DATA, IN_VLD, OUT_BUSY) is
	begin

		pc_increase <= '0';
		pc_decrease <= '0';
		ptr_increase <= '0';
		ptr_decrease <= '0';
		cnt_increase <= '0';
		cnt_decrease <= '0';
		mx_select <= "00";
		
		OUT_WREN <= '0';
		IN_REQ <= '0';
		CODE_EN <= '1';
		DATA_EN <= '0';
		
		
		
		case state is
		
			when state_fetch =>
				CODE_EN <= '1';
				nstate <= state_decode;

			when state_decode =>
				case CODE_DATA is
					when X"3E" => 
						nstate <= state_ptr_increase;
					when X"3C" => 
						nstate <= state_ptr_decrease;
					when X"2B" => 
						nstate <= state_var_increase;
					when X"2D" => 
						nstate <= state_var_decrease;
					when X"5B" => 
						nstate <= state_while_start;
					when X"5D" => 
						nstate <= state_while_end;					
					when X"2E" => 
						nstate <= state_write;
					when X"2C" => 
						nstate <= state_get;
					when X"7E" => 
						nstate <= state_break;
					when X"00" => 
						nstate <= state_null;
					when others => 
						pc_increase <= '1';
						nstate <= state_decode;
						
				end case;
				
			when state_ptr_increase =>
				ptr_increase <= '1';
				pc_increase <= '1';
				nstate <= state_fetch;

			when state_ptr_decrease =>
				ptr_decrease <= '1';
				pc_increase <= '1';
				nstate <= state_fetch;

			when state_var_increase =>
				DATA_EN <= '1';
				DATA_WREN <= '0';
				nstate <= state_var_increase_2;
				
			when state_var_increase_2 =>
				mx_select <= "01";
				DATA_EN <= '1';
				DATA_WREN <= '1';
				pc_increase <= '1';
				nstate <= state_fetch;

			when state_var_decrease =>
				DATA_EN <= '1';
				DATA_WREN <= '0';
				nstate <= state_var_decrease_2;
				
			when state_var_decrease_2 =>
				mx_select <= "10";
				DATA_EN <= '1';
				DATA_WREN <= '1';
				pc_increase <= '1';
				nstate <= state_fetch;

			when state_write =>
				if(OUT_BUSY = '1') then
					nstate <= state_write;
				else
					DATA_EN <= '1';
					DATA_WREN <= '0';
					nstate <= state_write_2;
				end if;
				
			when state_write_2 =>
				OUT_WREN <= '1';
				OUT_DATA <= DATA_RDATA;
				pc_increase <= '1';
				nstate <= state_fetch;

			when state_get =>
				IN_REQ <= '1';
				if(IN_VLD = '0') then
					nstate <= state_get;
				else
					mx_select <= "00";
					DATA_EN <= '1';
					DATA_WREN <= '1';
					pc_increase <= '1';
					nstate <= state_fetch;
				end if;

			when state_while_start =>
				pc_increase <= '1';
				DATA_EN <= '1';
				DATA_WREN <= '0';
				nstate <= state_while_start_2;
				
			when state_while_start_2 =>
				if(DATA_RDATA = X"00") then
					cnt_increase <= '1';
					nstate <= state_while_start_3;
				else
					nstate <= state_fetch;
				end if;
				
			when state_while_start_3 =>
				if(cnt_register = X"00") then
					nstate <= state_fetch;
				else
					CODE_EN <= '1';
					nstate <= state_while_start_4;
				end if;
				
			when state_while_start_4 =>	
				if(CODE_DATA = X"5B") then
					cnt_increase <= '1';
				elsif(CODE_DATA = X"5D") then
					cnt_decrease <= '1';
				end if;
				pc_increase <= '1';
				nstate <= state_while_start_3;

			when state_while_end =>
				DATA_EN <= '1';
				DATA_WREN <= '0';
				nstate <= state_while_end_2;
				
			when state_while_end_2 =>
				if(DATA_RDATA = X"00") then
					pc_increase <= '1';
					nstate <= state_fetch;
				else
					nstate <= state_while_end_3;
				end if;
				
			when state_while_end_3 =>
				cnt_increase <= '1';
				pc_decrease <= '1';
				nstate <= state_while_end_4;
				
			when state_while_end_4 =>
				if(cnt_register = X"00") then
					nstate <= state_fetch;
				else
					CODE_EN <= '1';
					nstate <= state_while_end_5;
				end if;
				
			when state_while_end_5 =>
				if(CODE_DATA = X"5D") then
					cnt_increase <= '1';
				elsif(CODE_DATA = X"5B") then
					cnt_decrease <= '1';
				end if;
				nstate <= state_while_end_6;
				
			when state_while_end_6 =>
				if(cnt_register = X"00") then
					pc_increase <= '1';
				else
					pc_decrease <= '1';
				end if;
				nstate <= state_while_end_4;

			when state_break =>
				cnt_increase <= '1';
				pc_increase <= '1';
				nstate <= state_break_2;
				
			when state_break_2 =>
				if(cnt_register = X"00") then
					nstate <= state_fetch;
				else
					CODE_EN <= '1';
					nstate <= state_break_3;
				end if;
			
			when state_break_3 =>
				if(CODE_DATA = X"5B") then
					cnt_increase <= '1';
				elsif(CODE_DATA = X"5D") then
					cnt_decrease <= '1';
				end if;
				pc_increase <= '1';
				nstate <= state_break_2;
			
			when state_null =>
				nstate <= state_null;

			when others => 
			   pc_increase <= '1';
				nstate <= state_decode;

		end case;

	end process next_state;

end behavioral;