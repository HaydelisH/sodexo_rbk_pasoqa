USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_clonar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Clona una Clausula
-- Ejemplo:exec sp_clausulas_clonar 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_clonar]
	@idClausula INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @Titulo_Cl VARCHAR (50)
	DECLARE @Descripcion_Cl VARCHAR (MAX)
	DECLARE	@Texto VARCHAR(MAX)
	DECLARE @idCategoria INT
	DECLARE @RutModificador VARCHAR (10)
	DECLARE @RutAprobador VARCHAR (10)
	DECLARE @RutEmpresa VARCHAR (10);
	  
	-- Se obtienen los datos necesarios
	SELECT @Titulo_Cl = Titulo_Cl, @Descripcion_Cl = Descripcion_Cl, @Texto = Texto,
	@idCategoria = idCategoria FROM Clausulas 
	WHERE idClausula = @idClausula
	
	--Asiignar un distintivo al titulo que se esta duplicando
	 SET @Titulo_Cl+= '(Copia)' 
	 SET @Descripcion_Cl+= '(Copia)'
	
	--Insertar datos a la tabla 
	INSERT INTO Clausulas( Titulo_Cl, Descripcion_Cl, Texto, idCategoria, 
	RutModificador, RutAprobador, Aprobado, Eliminado) 
	VALUES
	( @Titulo_Cl, @Descripcion_Cl, @Texto, @idCategoria,'','',0,0) 
	
	SELECT @@IDENTITY AS idClausula
END
GO
