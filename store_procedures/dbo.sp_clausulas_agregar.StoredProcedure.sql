USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_agregar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Agrega una Clausula
-- Ejemplo:exec sp_clausulas_agregar 'agregar',1,'','Titulo','Descripcion','Texto',1,'xxx','xxx','xxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_agregar]
	@pAccion CHAR(60),
	@Titulo_Cl VARCHAR (MAX),
	@Descripcion_Cl VARCHAR (MAX), 
	@Texto VARCHAR(MAX),
	@idCategoria INT,
	@RutModificador VARCHAR (10),
	@RutAprobador VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN	
		INSERT INTO Clausulas(Titulo_Cl, Descripcion_Cl, Texto, idCategoria, 
		RutModificador, RutAprobador, Aprobado,  Eliminado)
		VALUES
		(@Titulo_Cl, @Descripcion_Cl, @Texto, @idCategoria, @RutModificador,
		@RutAprobador, 0, 0)
		SELECT @@IDENTITY AS idClausula
    END 
    
END
GO
